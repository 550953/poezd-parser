<?php

error_reporting(E_ALL & ~E_DEPRECATED);
set_time_limit(0);

// if (php_sapi_name() !== 'cli') {
//     die("Скрипт можно запускать только из CLI\n");
// }

/*
|--------------------------------------------------------------------------
| Функция поиска города по коду станции
|--------------------------------------------------------------------------
*/
function findCityByStationCode($stationCode, $citiesData)
{
    if (!$stationCode || !$citiesData || !is_array($citiesData)) {
        return null;
    }

    foreach ($citiesData as $city) {
        if (!isset($city['stations'])) continue;

        foreach ($city['stations'] as $station) {
            if (($station['station_yandex_code'] ?? null) === $stationCode) {
                return $city['city_name'] ?? null;
            }
        }
    }

    return null;
}

/*
|--------------------------------------------------------------------------
| Проверка существования блока поезда
|--------------------------------------------------------------------------
*/
function trainBlockExists($newBlock, $existingData)
{
    foreach ($existingData as $block) {
        if (
            ($block['number'] ?? null) === ($newBlock['number'] ?? null) &&
            ($block['title'] ?? null) === ($newBlock['title'] ?? null) &&
            ($block['schedule']['dates'] ?? null) === ($newBlock['schedule']['dates'] ?? null)
        ) {
            return true;
        }
    }
    return false;
}

while (true) {

    echo "==============================\n";
    echo "Запуск: " . date('Y-m-d H:i:s') . "\n";
    echo "==============================\n";

    /*
    |--------------------------------------------------------------------------
    | Загружаем файл направлений
    |--------------------------------------------------------------------------
    */
    $routesFile = file_get_contents('train_way.json');
    $routesData = json_decode($routesFile, true);

    if (!is_array($routesData)) {
        echo "Ошибка чтения Направление поездов.json\n";
        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Загружаем станции
    |--------------------------------------------------------------------------
    */
    $stationsFile = file_get_contents('station_sity.json');
    $citiesData = json_decode($stationsFile, true);

    $filePath = __DIR__ . '/train_way.json';

    if (file_exists($filePath)) {
        $existingContent = file_get_contents($filePath);
        $existingData = json_decode($existingContent, true);
        if (!is_array($existingData)) {
            $existingData = [];
        }
    } else {
        $existingData = [];
    }

    /*
    |--------------------------------------------------------------------------
    | Перебор направлений
    |--------------------------------------------------------------------------
    */
    foreach ($routesData as $block) {

        $fromCode = $block['departure_code'] ?? null;
        $toCode = $block['arrival_code'] ?? null;

        if (!$fromCode || !$toCode) continue;

        echo "Обрабатывается: $fromCode → $toCode\n";

        /*
        |--------------------------------------------------------------------------
        | Формируем URL
        |--------------------------------------------------------------------------
        */
        $url = "https://api.rasp.yandex.net/v3.0/search/?" . http_build_query([
            'apikey' => 'd7b4a17b-32ba-4e77-9599-c29528ca1510',
            'from' => $fromCode,
            'to' => $toCode,
            'format' => 'json',
            'lang' => 'ru_RU',
            'transport_types' => 'train',
            'system' => 'yandex',
            'show_systems' => 'esr',
            'offset' => 0,
            'limit' => 500,
            'transfers' => 'false',
            'add_days_mask' => 'true'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Выполняем запрос через cURL
        |--------------------------------------------------------------------------
        */
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            echo "Ошибка cURL: " . curl_error($ch) . "\n";
            continue;
        }

        curl_close($ch);

        /*
        |--------------------------------------------------------------------------
        | Декодируем JSON
        |--------------------------------------------------------------------------
        */
        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Ошибка декодирования JSON\n";
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | Получаем коды станций
        |--------------------------------------------------------------------------
        */
        $fromCode = $data['search']['from']['code'] ?? null;
        $toCode = $data['search']['to']['code'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Получаем названия городов
        |--------------------------------------------------------------------------
        */
        $fromCity = findCityByStationCode($fromCode, $citiesData);
        $toCity = findCityByStationCode($toCode, $citiesData);

        /*
        |--------------------------------------------------------------------------
        | Формируем название маршрута
        |--------------------------------------------------------------------------
        */
        $segmentsTitleTrain = ($fromCity && $toCity)
            ? $fromCity . ' — ' . $toCity
            : null;

        /*
        |--------------------------------------------------------------------------
        | ФИЛЬТРАЦИЯ + ГРУППИРОВКА + ОБЪЕДИНЕНИЕ SCHEDULE
        |--------------------------------------------------------------------------
        */
        $groupedSegments = [];

        if (!empty($data['segments']) && $segmentsTitleTrain) {
            foreach ($data['segments'] as $segment) {

                $segmentTitle = $segment['thread']['title'] ?? null;

                if ($segmentTitle !== $segmentsTitleTrain) {
                    continue;
                }

                $number = $segment['thread']['number'] ?? null;

                if (!$number) {
                    continue;
                }

                $scheduleBlocks = $segment['schedule'] ?? [];

                if (!isset($groupedSegments[$number])) {
                    $groupedSegments[$number] = [
                        'number' => $number,
                        'title' => $segmentTitle,
                        'schedule' => []
                    ];
                }

                if (!is_array($scheduleBlocks)) {
                    continue;
                }

                foreach ($scheduleBlocks as $block) {
                    if (isset($block['year'])) {
                        $months = [$block];
                    } else {
                        $months = $block;
                    }

                    if (!is_array($months)) continue;

                    foreach ($months as $monthData) {
                        if (!is_array($monthData) || !isset($monthData['year'])) {
                            continue;
                        }

                        $year = $monthData['year'];
                        $month = $monthData['month'];
                        $days = $monthData['days'] ?? [];

                        $key = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);

                        if (!isset($groupedSegments[$number]['schedule'][$key])) {
                            $groupedSegments[$number]['schedule'][$key] = [
                                'year' => $year,
                                'month' => $month,
                                'days' => $days
                            ];
                        } else {
                            foreach ($days as $index => $dayValue) {
                                if ($dayValue == 1) {
                                    $groupedSegments[$number]['schedule'][$key]['days'][$index] = 1;
                                }
                            }
                        }
                    }
                }
            }

            // Сортировка месяцев и сброс ключей
            foreach ($groupedSegments as &$segment) {
                usort($segment['schedule'], function ($a, $b) {
                    return ($a['year'] . str_pad($a['month'], 2, '0', STR_PAD_LEFT)) <=> ($b['year'] . str_pad($b['month'], 2, '0', STR_PAD_LEFT));
                });

                $segment['schedule'] = array_values($segment['schedule']);
            }
            unset($segment);
        }

        /*
        |--------------------------------------------------------------------------
        | Преобразуем schedule в формат serialize(JSON)
        |--------------------------------------------------------------------------
        */
        foreach ($groupedSegments as &$segment) {
            if (!empty($segment['schedule']) && is_array($segment['schedule'])) {
                $datesList = [];

                foreach ($segment['schedule'] as $monthData) {
                    $year = $monthData['year'];
                    $month = $monthData['month'];
                    $days = $monthData['days'] ?? [];

                    foreach ($days as $index => $dayValue) {
                        // дни в API начинаются с 0 индекса
                        $dayNumber = $index + 1;

                        $datesList[] = [
                            'date' => $dayNumber . '.' . $month . '.' . $year,
                            'value' => $dayValue == 1 ? true : false
                        ];
                    }
                }

                // формируем JSON
                $segment['schedule'] = [
                    'dates' => $datesList
                ];
            }
        }

        unset($segment);

        /*
        |--------------------------------------------------------------------------
        | Формируем результат
        |--------------------------------------------------------------------------
        */
        $newResult = [
            'status' => 'success',
            'generated_at' => date('c'),
            'route_title' => $segmentsTitleTrain,
            'count' => count($groupedSegments),
            'segments' => array_values($groupedSegments)
        ];

        /*
        |--------------------------------------------------------------------------
        | Сохранение блоков поездов в файл
        |--------------------------------------------------------------------------
        */
        $filePath = __DIR__ . '/year_day.json';

        /**
         * Загружаем существующие данные
         */
        if (file_exists($filePath)) {
         $existingContent = file_get_contents($filePath);
         $existingData = json_decode($existingContent, true);

         if (!is_array($existingData)) {
         $existingData = [];
         }
        } else {
         $existingData = [];
        }


        /**
         * Добавляем или обновляем блоки
         */
        foreach ($groupedSegments as $segment) {

         $newBlock = [
         'number' => $segment['number'],
         'title' => $segment['title'],
         'schedule' => [
         'dates' => $segment['schedule']
         ]
         ];

         $found = false;

         foreach ($existingData as $index => $existingBlock) {

         if (
         $existingBlock['number'] === $newBlock['number'] &&
         $existingBlock['title'] === $newBlock['title']
         ) {
         // ✅ ОБНОВЛЯЕМ существующий блок
         $existingData[$index]['schedule']['dates'] = $newBlock['schedule']['dates'];
         $found = true;
         break;
         }
         }

         // ✅ Если не найден — добавляем новый
         if (!$found) {
         $existingData[] = $newBlock;
         }
        }


        /**
         * Сохраняем файл с блокировкой
         */
        file_put_contents(
         $filePath,
         json_encode(
         $existingData,
         JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
         ),
         LOCK_EX
        );

        echo "Цикл завершён. Ожидание 10 минут...\n\n";

        sleep(20); // Ожидание 10 минут
    }
}
