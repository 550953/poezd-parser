<?php

//header('Content-type: application/json');
error_reporting(E_ALL);
ini_set("display_errors", 1);
$host = '127.0.0.1'; // адрес сервера 
$database = 'provodnik'; // имя базы данных
$user = 'root'; // имя пользователя
$password = 'K2ClMv77SQT3gF3k'; // пароль

$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('memory_limit', '256M');


$arr = '[{"name": "СОРТАВАЛА ПР", "code": 2005572, "yandex_code": "s9885242"},

{"name": "ВАЛААМ", "code": 2005573, "yandex_code": "s9879175"},

{"name": "ФУРМАНОВСК", "code": 2708878, "yandex_code": "s9619884"},

{"name": "ТАСТЫ ТАЛД", "code": 2708879, "yandex_code": "s9619885"},

{"name": "БАРАНКУЛЬС", "code": 2708095, "yandex_code": "s9619805"},

{"name": "ДЕРЖАВИНСК", "code": 2708881, "yandex_code": "s9619887"},

{"name": "КЕНСКАЯ", "code": 2708882, "yandex_code": "s9619888"},

{"name": "ОП 63 КМ", "code": 2700224, "yandex_code": "s9871567"},

{"name": "ПРИИШИМСК", "code": 2708096, "yandex_code": "s9619806"},

{"name": "ОП 30 КМ", "code": 2700223, "yandex_code": "s9871564"},

{"name": "РЗД N1", "code": 2708622, "yandex_code": "s9619815"},

{"name": "КРАСИВ КЗХ", "code": 2708883, "yandex_code": "s9619889"},

{"name": "ОБП N80", "code": 2708097, "yandex_code": "s9619807"},

{"name": "КАЗАХСКАЯ", "code": 2708884, "yandex_code": "s9619890"},

{"name": "ПЕРЕКАТНАЯ", "code": 2708885, "yandex_code": "s9619891"},

{"name": "ОБП N86", "code": 2708808, "yandex_code": "s9619846"},

{"name": "ОП 591 КМ", "code": 2700192, "yandex_code": "s9871555"},

{"name": "ОП 614 КМ", "code": 2700193, "yandex_code": "s9871557"},

{"name": "АДЫР", "code": 2708886, "yandex_code": "s9619892"},

{"name": "ОП 628 КМ", "code": 2700194, "yandex_code": "s9871560"},

{"name": "КОЛУТОН", "code": 2708888, "yandex_code": "s9619894"},

{"name": "ОП 696 КМ", "code": 2708889, "yandex_code": "s9619895"},

{"name": "КАРА АДЫР", "code": 2708891, "yandex_code": "s9619897"},

{"name": "ОП 93 КМ", "code": 2700218, "yandex_code": "s9871562"},

{"name": "ТАСТАК", "code": 2708892, "yandex_code": "s9619898"},

{"name": "КОСЧЕКУ", "code": 2708893, "yandex_code": "s9619899"},

{"name": "ЖАЙНАК", "code": 2708894, "yandex_code": "s9619900"},

{"name": "ОП 795 КМ", "code": 2708099, "yandex_code": "s9619808"},

{"name": "ЕЛЬНЯ", "code": 2000869, "yandex_code": "s9601498"},

{"name": "СПАС ДЕМЕН", "code": 2000374, "yandex_code": "s9601037"},

{"name": "СУХИНИЧИ У", "code": 2000360, "yandex_code": "s9601023"},

{"name": "КОЗЕЛЬСК", "code": 2000383, "yandex_code": "s9601045"},

{"name": "ЧЕРЕПЕТЬ", "code": 2000199, "yandex_code": "s9600865"},

{"name": "БОБРИК ДОН", "code": 2000260, "yandex_code": "s9600926"},

{"name": "КИМОВСК", "code": 2000149, "yandex_code": "s9600815"},

{"name": "СКОПИН", "code": 2000161, "yandex_code": "s9600827"},

{"name": "БИНОКОР", "code": 2900735, "yandex_code": "s9620629"},

{"name": "ТУДАКУЛ", "code": 2900825, "yandex_code": "s9620707"},

{"name": "РОСТОВ ГЛ Т", "code": 2064675, "yandex_code": "s9879066"},

{"name": "МОСКВА КАЗ Т", "code": 2001109, "yandex_code": "s9879064"},

{"name": "КЫЗЫЛЖАР", "code": 2705789, "yandex_code": "s9757114"},

{"name": "ТУМАННОЕ", "code": 2704998, "yandex_code": "s9619527"},

{"name": "РЗД N16", "code": 2705863, "yandex_code": "s9619584"},

{"name": "БАКСАЙ", "code": 2704996, "yandex_code": "s9619525"},

{"name": "АК КИСТАУ", "code": 2704793, "yandex_code": "s9619330"},

{"name": "РЗД N15", "code": 2705864, "yandex_code": "s9619585"},

{"name": "РЗД N14", "code": 2700106, "yandex_code": "s9619025"},

{"name": "НАРЫН", "code": 2705865, "yandex_code": "s9619586"},

{"name": "САЗАНКУРАК", "code": 2705866, "yandex_code": "s9619587"},

{"name": "РЗД N11", "code": 2705867, "yandex_code": "s9619588"},

{"name": "ИСАТАЙ", "code": 2704792, "yandex_code": "s9619329"},

{"name": "РЗД N10", "code": 2705868, "yandex_code": "s9619589"},

{"name": "РЗД N9", "code": 2705869, "yandex_code": "s9619590"},

{"name": "АФАНАСЬЕВО", "code": 2704994, "yandex_code": "s9619524"},

{"name": "РЗД N7", "code": 2705870, "yandex_code": "s9619591"},

{"name": "ЖОЛТОБЕ", "code": 2705871, "yandex_code": "s9619592"},

{"name": "ГАНЮШКИНО", "code": 2704791, "yandex_code": "s9619328"},

{"name": "РЗД N5", "code": 2705872, "yandex_code": "s9619593"},

{"name": "РЗД N4", "code": 2705873, "yandex_code": "s9619594"},

{"name": "ДИНЫ НУРПЕ", "code": 2705874, "yandex_code": "s9619595"},

{"name": "РЗД N2", "code": 2700751, "yandex_code": "s9619162"},

{"name": "САРЫКУМ", "code": 2700042, "yandex_code": "s9834125"},

{"name": "РЗД N5", "code": 2700752, "yandex_code": "s9619163"},

{"name": "САЙКАН", "code": 2700753, "yandex_code": "s9619164"},

{"name": "РЗД N8", "code": 2700754, "yandex_code": "s9619165"},

{"name": "ОП 158 КМ", "code": 2700150, "yandex_code": "s9865591"},

{"name": "ЖАЙПАК", "code": 2700043, "yandex_code": "s9834126"},

{"name": "ОП 205 КМ", "code": 2700151, "yandex_code": "s9865592"},

{"name": "РЗД N16", "code": 2700162, "yandex_code": "s9865593"},

{"name": "РЗД 19", "code": 2700045, "yandex_code": "s9834128"},

{"name": "МИСКИН", "code": 2900104, "yandex_code": "s9620416"},

{"name": "ТУРТГУЛ", "code": 2900103, "yandex_code": "s9620415"},

{"name": "ЭЛЛИКАЛА", "code": 2900102, "yandex_code": "s9620414"},

{"name": "НУКУС", "code": 2900970, "yandex_code": "s9620748"},

{"name": "ТАХИАТАШ П", "code": 2900958, "yandex_code": "s9620743"},

{"name": "ХОДЖЕЙЛИ", "code": 2900830, "yandex_code": "s9620711"},

{"name": "КУНГРАД", "code": 2900885, "yandex_code": "s9620734"},

{"name": "ДАВЫДОВКА", "code": 2014502, "yandex_code": "s9605146"},

{"name": "РЯБОВО", "code": 2004145, "yandex_code": "s9602638"},

{"name": "ТРУБНИКОВО", "code": 2005363, "yandex_code": "s9603748"},

{"name": "ТОРФЯНОЕ", "code": 2004442, "yandex_code": "s9602935"},

{"name": "ТРЕГУБОВО", "code": 2004131, "yandex_code": "s9602624"},

{"name": "МЯСНОЙ БОР", "code": 2004129, "yandex_code": "s9602622"},

{"name": "РЗД 64 КМ", "code": 2004935, "yandex_code": "s9603336"},

{"name": "ПАВЛОВСКАЯ", "code": 2005312, "yandex_code": "s9603700"},

{"name": "СТ САМБОР", "code": 2218112, "yandex_code": "s9617649"},

{"name": "ТУРКА", "code": 2218235, "yandex_code": "s9617772"},

{"name": "СЯНКИ", "code": 2218118, "yandex_code": "s9617655"},

{"name": "КОСТРИНО", "code": 2218911, "yandex_code": "s9618045"},

{"name": "ВЕЛ БЕРЕЗ", "code": 2218122, "yandex_code": "s9617659"},

{"name": "ПЕРЕЧИН", "code": 2218123, "yandex_code": "s9617660"},

{"name": "НОВОСИБ ЮЖ", "code": 2044004, "yandex_code": "s9610192"},

{"name": "БАСТАУ", "code": 2700046, "yandex_code": "s9841399"},

{"name": "АКСУ 1", "code": 2709928, "yandex_code": "s9620164"},

{"name": "КУРКОЛЬ", "code": 2709900, "yandex_code": "s9620153"},

{"name": "КОКТОБЕ", "code": 2709901, "yandex_code": "s9620154"},

{"name": "ЖУМЫСКЕР", "code": 2709902, "yandex_code": "s9620155"},

{"name": "МАЙ", "code": 2709903, "yandex_code": "s9620156"},

{"name": "ВОРОНЕНКА", "code": 2218216, "yandex_code": "s9617753"},

{"name": "ЛАЗЕЩИНА", "code": 2218214, "yandex_code": "s9617751"},

{"name": "ВЫХИНО", "code": 2001000, "yandex_code": "s9601627"},

{"name": "РЕДКИНО", "code": 2004453, "yandex_code": "s9602946"},

{"name": "КАРЕЛЬСКАЯ", "code": 2005577, "yandex_code": "s9879636"},

{"name": "СОРТАВАЛА Т", "code": 2005565, "yandex_code": "s9879067"},

{"name": "ТВЕРЬ ТУР", "code": 2005575, "yandex_code": "s9879187"},

{"name": "МОСКВА ОКТ Т", "code": 2005492, "yandex_code": "s9878667"},

{"name": "ОП 296 КМ", "code": 2005403, "yandex_code": "s9603788"},

{"name": "ТЕМИРБУЛАК", "code": 2708119, "yandex_code": "s9619814"},

{"name": "КАРАТОМАР", "code": 2708805, "yandex_code": "s9619844"},

{"name": "ОП 348 КМ", "code": 2700215, "yandex_code": "s9871630"},

{"name": "ВОРОНИНСК", "code": 2709937, "yandex_code": "s9620171"},

{"name": "УВАЛЬНЕНСК", "code": 2708806, "yandex_code": "s9619845"},

{"name": "ОБП N75", "code": 2708784, "yandex_code": "s9619834"},

{"name": "ГОГОЛЕВО", "code": 2204567, "yandex_code": "s9615877"},

{"name": "РЕШЕТИЛОВК", "code": 2204573, "yandex_code": "s9615882"},

{"name": "ГРАКОВО", "code": 2204424, "yandex_code": "s9615757"},

{"name": "КИСЛОВКА", "code": 2204406, "yandex_code": "s9615742"},

{"name": "КУЗЕМОВКА", "code": 2204407, "yandex_code": "s9615743"},

{"name": "И КУЛЬТУРЫ", "code": 2100002, "yandex_code": "s9613990"},

{"name": "САГУНЫ", "code": 2014547, "yandex_code": "s9605186"},

{"name": "ЧЕХОВ", "code": 2000400, "yandex_code": "s9601062"},

{"name": "АЙРЫТАМ", "code": 2900788, "yandex_code": "s9620673"},

{"name": "БЕРЕЗ ВОСТ", "code": 2050479, "yandex_code": "s9611155"},

{"name": "ВАСИЛЬЕВО", "code": 2061423, "yandex_code": "s9612675"},

{"name": "ЮШКИ", "code": 2100252, "yandex_code": "s9614240"},

{"name": "ГОРОЧИЧИ", "code": 2100251, "yandex_code": "s9889275"},

{"name": "ХОЛОДНИКИ", "code": 2100229, "yandex_code": "s9614217"},

{"name": "ШЕРЕГЕШ", "code": 2028810, "yandex_code": "s9607293"},

{"name": "ТАШТАГОЛ П", "code": 2028126, "yandex_code": "s9607186"},

{"name": "КИЦА", "code": 2004398, "yandex_code": "s9602891"},

{"name": "БОЯРСКАЯ", "code": 2004378, "yandex_code": "s9602871"},

{"name": "ПОНЬГОМА", "code": 2004375, "yandex_code": "s9602868"},

{"name": "ЛАМБИНО", "code": 2004374, "yandex_code": "s9602867"},

{"name": "ШУМАКОВО", "code": 2014337, "yandex_code": "s9604997"},

{"name": "НИЗИНА", "code": 2050613, "yandex_code": "s9611230"},

{"name": "СОЛОНОВО", "code": 2020059, "yandex_code": "s9605618"},

{"name": "РОГОЖИНО", "code": 2020054, "yandex_code": "s9605613"},

{"name": "МАХАМБЕТ", "code": 2704794, "yandex_code": "s9619331"},

{"name": "ТЕНДЫК", "code": 2704796, "yandex_code": "s9619333"},

{"name": "РЗД 496 КМ", "code": 2705861, "yandex_code": "s9619583"},

{"name": "КАРАБАТАНО", "code": 2704797, "yandex_code": "s9619334"},

{"name": "ТАСКЕСКЕН", "code": 2705860, "yandex_code": "s9619582"},

{"name": "ИСКИНЕ", "code": 2704798, "yandex_code": "s9619335"},

{"name": "РЗД 441 КМ", "code": 2705848, "yandex_code": "s9619570"},

{"name": "РЗД 414 КМ", "code": 2705847, "yandex_code": "s9619569"},

{"name": "РЗД 402 КМ", "code": 2705846, "yandex_code": "s9619568"},

{"name": "РЗД N472", "code": 2705849, "yandex_code": "s9619571"},

{"name": "БЕКБЕКЕ", "code": 2704823, "yandex_code": "s9619360"},

{"name": "БАЙТЕРЕК", "code": 2705788, "yandex_code": "s9757108"},

{"name": "РЗД N470", "code": 2705850, "yandex_code": "s9619572"},

{"name": "РЗД N469", "code": 2705851, "yandex_code": "s9619573"},

{"name": "ОП 1254 КМ", "code": 2700212, "yandex_code": "s9871455"},

{"name": "ШОКПАРТОГАЙ", "code": 2705787, "yandex_code": "s9757107"},

{"name": "РЗД N468", "code": 2705852, "yandex_code": "s9619574"},

{"name": "РЗД N467", "code": 2705853, "yandex_code": "s9619575"},

{"name": "РЗД N465", "code": 2705854, "yandex_code": "s9619576"},

{"name": "РЗД N464", "code": 2705855, "yandex_code": "s9619577"},

{"name": "РЗД N463", "code": 2705856, "yandex_code": "s9619578"},

{"name": "КОРКОЛ", "code": 2704818, "yandex_code": "s9619355"},

{"name": "РЗД N461", "code": 2705857, "yandex_code": "s9619579"},

{"name": "РЗД N460", "code": 2705881, "yandex_code": "s9619600"},

{"name": "РЗД N1", "code": 2700417, "yandex_code": "s9619072"},

{"name": "РЗД N2 Г", "code": 2704816, "yandex_code": "s9619353"},

{"name": "РЗД N3", "code": 2700267, "yandex_code": "s9871457"},

{"name": "РЗД N5", "code": 2700266, "yandex_code": "s9871456"},

{"name": "РЗД N7", "code": 2700268, "yandex_code": "s9871458"},

{"name": "РЗД N9", "code": 2700269, "yandex_code": "s9871459"},

{"name": "МАНАТА", "code": 2705784, "yandex_code": "s9826083"},

{"name": "РЗД N11", "code": 2700270, "yandex_code": "s9871460"},

{"name": "РЗД N12", "code": 2704809, "yandex_code": "s9619346"},

{"name": "РЗД N13", "code": 2700273, "yandex_code": "s9871461"},

{"name": "РЗД N14", "code": 2700272, "yandex_code": "s9871462"},

{"name": "РЗД N15", "code": 2705877, "yandex_code": "s9619598"},

{"name": "РЗД N16", "code": 2700271, "yandex_code": "s9871463"},

{"name": "ТАМАК", "code": 2705785, "yandex_code": "s9826084"},

{"name": "ЧЕРНИГОВКА", "code": 2040690, "yandex_code": "s9609658"},

{"name": "БУЛАЕВО 1", "code": 2040539, "yandex_code": "s9609507"},

{"name": "КИЯЛЫ", "code": 2708867, "yandex_code": "s9619874"},

{"name": "АЗАТ", "code": 2708869, "yandex_code": "s9619876"},

{"name": "ТАКУ", "code": 2054141, "yandex_code": "s9611489"},

{"name": "САЛЛИКИТ", "code": 2054231, "yandex_code": "s9611571"},

{"name": "УФИМКА", "code": 2060555, "yandex_code": "s9612358"},

{"name": "УЛАН БАТОР", "code": 3100022, "yandex_code": "s9620787"},

{"name": "ДЗУН ХАРА", "code": 3100012, "yandex_code": "s9620784"},

{"name": "ДАРХАН", "code": 3100008, "yandex_code": "s9620782"},

{"name": "СУХБААТАР", "code": 3100019, "yandex_code": "s9620786"},

{"name": "ЖАНАТУРМЫС", "code": 2708634, "yandex_code": "s9619825"},

{"name": "ШАХОВСКИЙ", "code": 2708083, "yandex_code": "s9619798"},

{"name": "КАУДАНДЫ", "code": 2708084, "yandex_code": "s9619799"},

{"name": "ЖАНАЖОЛ", "code": 2708085, "yandex_code": "s9619800"},

{"name": "ШАГЫЛЫ", "code": 2700442, "yandex_code": "s9871905"},

{"name": "ТЕНИЗ", "code": 2708086, "yandex_code": "s9619801"},

{"name": "ОП 111 КМ", "code": 2700159, "yandex_code": "s9871634"},

{"name": "ПРИРЕЧНАЯ", "code": 2708868, "yandex_code": "s9619875"},

{"name": "ОП 165 КМ", "code": 2700173, "yandex_code": "s9871635"},

{"name": "ОП 169 КМ", "code": 2700176, "yandex_code": "s9871636"},

{"name": "ЖАМАН АЩИ", "code": 2708088, "yandex_code": "s9619802"},

{"name": "ЧАГЛИНКА", "code": 2708871, "yandex_code": "s9619878"},

{"name": "ОП 259 КМ", "code": 2700179, "yandex_code": "s9634427"},

{"name": "ОП 274 КМ", "code": 2700186, "yandex_code": "s9871637"},

{"name": "КОКШЕТАУ 2", "code": 2708658, "yandex_code": "s9619831"},

{"name": "ТРОФИМОВКА", "code": 2708865, "yandex_code": "s9619872"},

{"name": "ОКТЯБРЬ", "code": 2708864, "yandex_code": "s9619871"},

{"name": "ЧКАЛОВО", "code": 2708863, "yandex_code": "s9619870"},

{"name": "ЗОЛОТОРУНН", "code": 2708862, "yandex_code": "s9619869"},

{"name": "АЩИ ГОЛЬ", "code": 2708861, "yandex_code": "s9619868"},

{"name": "ДАУТ", "code": 2708859, "yandex_code": "s9619866"},

{"name": "ТАЛЬЩИК", "code": 2708858, "yandex_code": "s9619865"},

{"name": "КЗЫЛ ТУ", "code": 2708857, "yandex_code": "s9619864"},

{"name": "КУКУШТАН", "code": 2030026, "yandex_code": "s9607430"},

{"name": "ТУЛЮШКА", "code": 2054799, "yandex_code": "s9611739"},

{"name": "АЛТАЙ", "code": 2700820, "yandex_code": "s9619220"},

{"name": "ОП 174 КМ", "code": 2700107, "yandex_code": "s9619026"},

{"name": "ТУРГУСУН", "code": 2700694, "yandex_code": "s9619112"},

{"name": "ОП 148 КМ", "code": 2700434, "yandex_code": "s9619088"},

{"name": "ЗАВОДИНКА", "code": 2700693, "yandex_code": "s9619111"},

{"name": "ОП 136 КМ", "code": 2700064, "yandex_code": "s9618993"},

{"name": "ОП 122 КМ", "code": 2700433, "yandex_code": "s9619087"},

{"name": "БУХТАРМА", "code": 2700691, "yandex_code": "s9619110"},

{"name": "КОКЖИЕК", "code": 2700689, "yandex_code": "s9619108"},

{"name": "КУМЫСТАУ", "code": 2700688, "yandex_code": "s9619107"},

{"name": "ОП 85 КМ", "code": 2700286, "yandex_code": "s9872460"},

{"name": "ТАУСАМАЛЫ", "code": 2700916, "yandex_code": "s9619295"},

{"name": "ОП 57 КМ", "code": 2700078, "yandex_code": "s9872459"},

{"name": "ОП 56 КМ", "code": 2700261, "yandex_code": "s9871642"},

{"name": "ОП 51 КМ", "code": 2700260, "yandex_code": "s9871641"},

{"name": "ОП 49 КМ", "code": 2700432, "yandex_code": "s9619086"},

{"name": "ОП 45 КМ", "code": 2700431, "yandex_code": "s9619085"},

{"name": "ОП 44 КМ", "code": 2700259, "yandex_code": "s9871640"},

{"name": "ОП 42 КМ", "code": 2700258, "yandex_code": "s9872458"},

{"name": "ЕРМАКОВКА", "code": 2700686, "yandex_code": "s9619106"},

{"name": "ОП 37 КМ", "code": 2700257, "yandex_code": "s9872457"},

{"name": "ОП 36 КМ", "code": 2700256, "yandex_code": "s9871639"},

{"name": "ОП 33 КМ", "code": 2700056, "yandex_code": "s9618985"},

{"name": "ОП 28 КМ", "code": 2700255, "yandex_code": "s9872456"},

{"name": "ОП 26 КМ", "code": 2700254, "yandex_code": "s9872455"},

{"name": "ОП 23 КМ", "code": 2700055, "yandex_code": "s9618984"},

{"name": "ОП 22 КМ", "code": 2700253, "yandex_code": "s9872454"},

{"name": "ОП 19 КМ", "code": 2700252, "yandex_code": "s9872453"},

{"name": "ОП 16 КМ", "code": 2700251, "yandex_code": "s9872452"},

{"name": "АССАКЕ", "code": 2900697, "yandex_code": "s9620600"},

{"name": "КУВА", "code": 2900701, "yandex_code": "s9620604"},

{"name": "ФУРКАТ", "code": 2900712, "yandex_code": "s9620614"},

{"name": "ЧОДАК", "code": 2901307, "yandex_code": "s9858545"},

{"name": "АКТОГАЙ В", "code": 2700102, "yandex_code": "s9619021"},

{"name": "ОП 1013 КМ", "code": 2705821, "yandex_code": "s9867628"},

{"name": "УЙТАС", "code": 2704710, "yandex_code": "s9619309"},

{"name": "ОП 1037 КМ", "code": 2705820, "yandex_code": "s9867627"},

{"name": "ОП 1050 КМ", "code": 2705818, "yandex_code": "s9634422"},

{"name": "КУДЫКСАЙ", "code": 2704709, "yandex_code": "s9619308"},

{"name": "ОП 1070 КМ", "code": 2705814, "yandex_code": "s9867626"},

{"name": "ОП 1080 КМ", "code": 2705812, "yandex_code": "s9867625"},

{"name": "ЖОСА", "code": 2704708, "yandex_code": "s9619307"},

{"name": "ОП 1097 КМ", "code": 2705809, "yandex_code": "s9867624"},

{"name": "ОП 1129 КМ", "code": 2705806, "yandex_code": "s9867623"},

{"name": "САРЫСАЙ", "code": 2704706, "yandex_code": "s9619305"},

{"name": "ОП 12 КМ", "code": 2705805, "yandex_code": "s9867622"},

{"name": "ЕЛИМАЙ", "code": 2708109, "yandex_code": "s9610100"},

{"name": "ОП 55 КМ", "code": 2708108, "yandex_code": "s9609987"},

{"name": "ЗААЯТСКАЯ", "code": 2708107, "yandex_code": "s9609358"},

{"name": "ОП 76 КМ", "code": 2700227, "yandex_code": "s9871596"},

{"name": "ОП 90 КМ", "code": 2700231, "yandex_code": "s9871597"},

{"name": "БАТАЛЫ", "code": 2708106, "yandex_code": "s9609360"},

{"name": "ОП 115 КМ", "code": 2700233, "yandex_code": "s9871598"},

{"name": "ОП 128 КМ", "code": 2708105, "yandex_code": "s9609678"},

{"name": "МЕРКЕ", "code": 5900893, "yandex_code": "s9621794"},

{"name": "КАИНДЫ", "code": 5900895, "yandex_code": "s9621796"},

{"name": "КАРАБАЛТА", "code": 5900992, "yandex_code": "s9621808"},

{"name": "БЕЛОВОДСК", "code": 5900896, "yandex_code": "s9621797"},

{"name": "БИШКЕК 2", "code": 5900000, "yandex_code": "s9621770"},

{"name": "СПБ ПР КУНСТ", "code": 2005580, "yandex_code": "s9888865"},

{"name": "КРОНШТАДТ ЗИ", "code": 2005581, "yandex_code": "s9888864"},

{"name": "БИШКЕК 1", "code": 5900898, "yandex_code": "s9621799"},

{"name": "ШОПОКОВО", "code": 5900897, "yandex_code": "s9621798"},

{"name": "ЛЕДМОЗЕРО 2", "code": 2005347, "yandex_code": "s9634676"},

{"name": "ИПАТОВО", "code": 2064239, "yandex_code": "s9613140"},

{"name": "КУЛТУК", "code": 2054652, "yandex_code": "s9611650"},

{"name": "СТАРАЯ АНГАС", "code": 2054147, "yandex_code": "s9872469"},

{"name": "КИРКИРЕЙ", "code": 2054148, "yandex_code": "s9870680"},

{"name": "ПОЛОВИННАЯ", "code": 2054204, "yandex_code": "s9611544"},

{"name": "ИТАЛ СТЕНКА", "code": 2054246, "yandex_code": "s9879236"},

{"name": "МАЛЫЙ БАРАНЧ", "code": 2054211, "yandex_code": "s9611551"},

{"name": "БАЙКАЛ", "code": 2054658, "yandex_code": "s9611656"},

{"name": "ИРКУТСК ТУР", "code": 2054248, "yandex_code": "s9881289"},

{"name": "УЛАН-УДЭ ТУР", "code": 2054249, "yandex_code": "s9881290"},

{"name": "МОСКВА САВ", "code": 2000009, "yandex_code": "s2000009"},

{"name": "ОКРУЖНАЯ", "code": 2001270, "yandex_code": "s9601830"},

{"name": "ДОЛГОПРУД", "code": 2000100, "yandex_code": "s9600766"},

{"name": "ДМИТРОВ", "code": 2000026, "yandex_code": "s9600692"},

{"name": "ВЕРБИЛКИ", "code": 2000027, "yandex_code": "s9600693"},

{"name": "БОЛЬШ ВОЛГ", "code": 2001131, "yandex_code": "s9601720"},

{"name": "ДУБНА", "code": 2001026, "yandex_code": "s9601639"},

{"name": "АУЛ", "code": 2700701, "yandex_code": "s9619116"},

{"name": "РЗД N41", "code": 2700086, "yandex_code": "s9619012"},

{"name": "БЕЛЬ АГАЧ", "code": 2700705, "yandex_code": "s9619119"},

{"name": "ДЮСАКЕН", "code": 2700707, "yandex_code": "s9619120"},

{"name": "ШОПТИКАК", "code": 2700715, "yandex_code": "s9619127"},

{"name": "АКБАЛЫК", "code": 2700766, "yandex_code": "s9619172"},

{"name": "БАЙСЕРКЕ", "code": 2700807, "yandex_code": "s9619208"},

{"name": "КАЗЫБЕК БЕ", "code": 2700813, "yandex_code": "s9619213"},

{"name": "ЧИЛЬБАСТАУ", "code": 2700816, "yandex_code": "s9619216"},

{"name": "СОРТАВ Ч ВАЛ", "code": 2005582, "yandex_code": "s9879174"},

{"name": "НИГОЗЕРО", "code": 2004335, "yandex_code": "s9602828"},

{"name": "КОТЕЛ", "code": 2014353, "yandex_code": "s9605011"},

{"name": "АЭРО СОЧИ", "code": 2064166, "yandex_code": "s9882415"},

{"name": "ОРЛЕНОК", "code": 2041611, "yandex_code": "s9609861"},

{"name": "ОП 2579 КМ", "code": 2040567, "yandex_code": "s9609535"},

{"name": "МАМЛЮТКА", "code": 2040532, "yandex_code": "s9609500"},

{"name": "ОП 2591 КМ", "code": 2040737, "yandex_code": "s9609704"},

{"name": "КОНДРАТ С", "code": 2040533, "yandex_code": "s9609501"},

{"name": "ОП 2603 КМ", "code": 2041616, "yandex_code": "s9609865"},

{"name": "ЗАТОН", "code": 2041745, "yandex_code": "s9609991"},

{"name": "ОП 131 КМ", "code": 2043660, "yandex_code": "s9610132"},

{"name": "НИЖЕГОРОДСКА", "code": 2001280, "yandex_code": "s9601835"},

{"name": "ПЕТУШКИ", "code": 2060336, "yandex_code": "s9612144"},

{"name": "МАРИАНОВКА", "code": 2044686, "yandex_code": "s9610370"},

{"name": "КРУТОЕ", "code": 2001925, "yandex_code": "s9602107"},

{"name": "РУЧЬИ КАР", "code": 2004382, "yandex_code": "s9602875"},

{"name": "БЕЛОЕ МОРЕ", "code": 2004383, "yandex_code": "s9602876"},

{"name": "КАРАКАЛПАКСТ", "code": 2900879, "yandex_code": "s9620728"},

{"name": "ЯГЕЛЬН БОР", "code": 2004394, "yandex_code": "s9602887"},

{"name": "ГОРЕЛ МОСТ", "code": 2004361, "yandex_code": "s9602854"},

{"name": "РУДНЫЙ", "code": 2004393, "yandex_code": "s9602886"},

{"name": "ПУХОВО", "code": 2014544, "yandex_code": "s9605183"},

{"name": "ИБРЕСИ", "code": 2060571, "yandex_code": "s9612374"},

{"name": "ЦИВИЛЬСК", "code": 2060565, "yandex_code": "s9612368"},

{"name": "МУЛЬМУГАКАН", "code": 2034542, "yandex_code": "s9634715"},

{"name": "НЮРИХ", "code": 2030529, "yandex_code": "s9607870"},

{"name": "ПАНТЫНГ", "code": 2030527, "yandex_code": "s9607869"},

{"name": "БОКСИТЫ", "code": 2030286, "yandex_code": "s9607682"},

{"name": "ЛЕСНАЯ ВОЛ", "code": 2030159, "yandex_code": "s9607562"},

{"name": "КАРПИНСК", "code": 2030156, "yandex_code": "s9607559"},

{"name": "КРАСНОТУР", "code": 2030157, "yandex_code": "s9607560"},

{"name": "БОЛ ВИШЕРА", "code": 2004139, "yandex_code": "s9602632"},

{"name": "ВТОР РЕЧКА", "code": 2034461, "yandex_code": "s9608420"},

{"name": "ИЛИЙСКАЯ", "code": 2700050, "yandex_code": "s9618979"},

{"name": "СТУПИНО", "code": 2000540, "yandex_code": "s9601202"},

{"name": "ЯЗЕВКА СИБ", "code": 2044863, "yandex_code": "s9610546"},

{"name": "МИХ ЧЕСНОК", "code": 2050473, "yandex_code": "s9611149"},

{"name": "ПИЛЬШИНО", "code": 2000437, "yandex_code": "s9601099"},

{"name": "ВИЗ", "code": 2030200, "yandex_code": "s9607601"},

{"name": "ПЕРВОМАЙСК", "code": 2031190, "yandex_code": "s9608066"},

{"name": "РЗД N7", "code": 2700723, "yandex_code": "s9619135"},

{"name": "ТЕП СТАН", "code": 2024503, "yandex_code": "s9606267"},

{"name": "РОСТОВ ГЛ ПР", "code": 2064690, "yandex_code": "s9888866"},

{"name": "ТАРХАНЫ", "code": 2020795, "yandex_code": "s9605839"},

{"name": "БИЧЕВНОЙ", "code": 2024632, "yandex_code": "s9606396"},

{"name": "БАЛАКИРЕВО", "code": 2010235, "yandex_code": "s9604157"},

{"name": "ШУВАКИШ", "code": 2030472, "yandex_code": "s9607822"},

{"name": "В ПЫШМА МУЗ", "code": 2030302, "yandex_code": "s9883057"},

{"name": "ШУВАКИШ ТУР", "code": 2030303, "yandex_code": "s9886196"},

{"name": "ЕКАТЕРИНБ Т", "code": 2030304, "yandex_code": "s9886195"},

{"name": "ПОВАРОВО 1", "code": 2004432, "yandex_code": "s9602925"},

{"name": "ВЫБОРГ Т", "code": 2005566, "yandex_code": "s9879068"},

{"name": "ПЕЧАТНИКИ", "code": 2001135, "yandex_code": "s9881845"},

{"name": "ЛЮБЛИНО", "code": 2001220, "yandex_code": "s9601788"},

{"name": "КУЯР", "code": 2060596, "yandex_code": "s9612399"},

{"name": "САРАЕВКА", "code": 2014339, "yandex_code": "s9604999"},

{"name": "МАКАРОВО", "code": 2010193, "yandex_code": "s9604116"},

{"name": "ДАШТОБОД", "code": 2900775, "yandex_code": "s9620661"},

{"name": "ПЕТРОЗАВ ТУР", "code": 2005597, "yandex_code": "s9887140"},

{"name": "ЧУДОВО Т", "code": 2005568, "yandex_code": "s9881810"},

{"name": "УЧКУДУК 2", "code": 2900105, "yandex_code": "s9620417"},

{"name": "ХУДЖАНД", "code": 6600910, "yandex_code": "s9621897"},

{"name": "МЕРЗЛОТНАЯ", "code": 2054379, "yandex_code": "s9611628"},

{"name": "ИЛЕЦК 1", "code": 2704820, "yandex_code": "s9619357"},

{"name": "ХИВА", "code": 2900172, "yandex_code": "s9870997"},

{"name": "ЛЮБЕРЦЫ 1", "code": 2001020, "yandex_code": "s9601636"},

{"name": "ОЯШ", "code": 2044779, "yandex_code": "s9654586"},

{"name": "ЮРЬЕВЕЦ", "code": 2060342, "yandex_code": "s9612149"},

{"name": "ЯНИССИЛТА", "code": 2005491, "yandex_code": "s9884242"},

{"name": "КОСАРКА", "code": 2020945, "yandex_code": "s9605988"},

{"name": "ОКУНАЙСКИЙ", "code": 2054213, "yandex_code": "s9611553"},

{"name": "ЛЕНА ВОСТ", "code": 2054043, "yandex_code": "s9611393"},

{"name": "ПЕРЕМЕТНАЯ", "code": 2704957, "yandex_code": "s9619489"},

{"name": "ТАСКАЛА", "code": 2704954, "yandex_code": "s9619486"},

{"name": "СЕМИГЛ МАР", "code": 2704952, "yandex_code": "s9619484"},

{"name": "ОЗИНКИ", "code": 2020890, "yandex_code": "s9605934"},

{"name": "АЛТАТА", "code": 2020892, "yandex_code": "s9605936"},

{"name": "ЧАРТАК", "code": 2900688, "yandex_code": "s9620592"},

{"name": "НАМАНГАН", "code": 2900940, "yandex_code": "s9620739"},

{"name": "ЧУСТ", "code": 2900692, "yandex_code": "s9620595"},

{"name": "ЧЕРНЫЕ КАМНИ", "code": 2005576, "yandex_code": "s9879481"},

{"name": "РЗД 377 КМ", "code": 2705845, "yandex_code": "s9619567"},

{"name": "КЕНБАЙ", "code": 2705844, "yandex_code": "s9619566"},

{"name": "РЗД 279 КМ", "code": 2705843, "yandex_code": "s9619565"},

{"name": "РЗД 265 КМ", "code": 2705842, "yandex_code": "s9619564"},

{"name": "РЗД 236 КМ", "code": 2705841, "yandex_code": "s9619563"},

{"name": "РЗД 202 КМ", "code": 2700167, "yandex_code": "s9872307"},

{"name": "РЗД 174 КМ", "code": 2705839, "yandex_code": "s9619561"},

{"name": "РЗД 142", "code": 2700040, "yandex_code": "s9834122"},

{"name": "КЕНЖАЛЫ", "code": 2704827, "yandex_code": "s9619364"},

{"name": "РЗД 102", "code": 2700009, "yandex_code": "s9834121"},

{"name": "ЖАКСЫМАЙ", "code": 2704829, "yandex_code": "s9619366"},

{"name": "ТЕМИР", "code": 2704832, "yandex_code": "s9619369"},

{"name": "ГОЙТХ", "code": 2064091, "yandex_code": "s9613003"},

{"name": "ЗАСТРУГ", "code": 2060628, "yandex_code": "s9612429"},

{"name": "ЭРКЕН ШАХ", "code": 2064127, "yandex_code": "s9613032"},

{"name": "ИМ В ГРУШИНА", "code": 2025869, "yandex_code": "s9606830"},

{"name": "МОСКВА ВОС Т", "code": 2001225, "yandex_code": "s9881812"},

{"name": "ЧИТА 1", "code": 2050002, "yandex_code": "s9610945"},

{"name": "ГАГРЫПШ", "code": 4200302, "yandex_code": "s9637568"},

{"name": "АБААТА", "code": 4200301, "yandex_code": "s9637567"},

{"name": "ДАУТЕПА", "code": 2900114, "yandex_code": "s9881783"},

{"name": "С-ПЕТЕР СП Л", "code": 2005593, "yandex_code": "s9888869"},

{"name": "ПЕТЕРГОФ ПР", "code": 2005592, "yandex_code": "s9799192"},

{"name": "БОЛОГОЕ ТУР", "code": 2005598, "yandex_code": "s9887157"},

{"name": "СОСНОВО", "code": 2004683, "yandex_code": "s9603176"},

{"name": "ДУШАНБЕ 1", "code": 6600000, "yandex_code": "s9621860"},

{"name": "ВЕТКА", "code": 2100351, "yandex_code": "s9768999"},

{"name": "СУЗДАЛЬ", "code": 2060051, "yandex_code": "s9612115"},

{"name": "МОСКВА КИВ Т", "code": 2001115, "yandex_code": "s9887158"},

{"name": "ГЛУБОКАЯ", "code": 2064568, "yandex_code": "s9613455"},

{"name": "КИРИЛЛОВ", "code": 2010467, "yandex_code": "s9623253"},

{"name": "КРЕП ОРЕШЕК", "code": 2005600, "yandex_code": "s9834282"},

{"name": "МАЦЕСТА", "code": 2064064, "yandex_code": "s9612976"},

{"name": "ЗВЕЗДА", "code": 2024608, "yandex_code": "s9606372"},

{"name": "ПЕРСИАНОВК", "code": 2064003, "yandex_code": "s9612915"},

{"name": "МОРОЗНАЯ", "code": 2006216, "yandex_code": "s9886719"},

{"name": "КЭЛЭРАШЬ", "code": 2300587, "yandex_code": "s9618135"},

{"name": "УСПЕНСКАЯ", "code": 2064249, "yandex_code": "s9613150"},

{"name": "КОМС-СОРТ", "code": 2035702, "yandex_code": "s9608684"},

{"name": "БП 704 КМ", "code": 2005507, "yandex_code": "s9655409"},

{"name": "ШАВАНЬ", "code": 2005356, "yandex_code": "s9603741"},

{"name": "МАЙ ГУБА", "code": 2004355, "yandex_code": "s9602848"},

{"name": "ФАНИПОЛЬ", "code": 2100109, "yandex_code": "s9614097"},

{"name": "СПИТАМЕН", "code": 6600771, "yandex_code": "s2900239"},

{"name": "ВОДОЛАЗОВО", "code": 2040507, "yandex_code": "s9609475"},

{"name": "МЫШАСТОВКА", "code": 2064158, "yandex_code": "s9613062"},

{"name": "МАЛЫГА", "code": 2004348, "yandex_code": "s9602841"},

{"name": "ГРЕМ КЛЮЧ", "code": 2024629, "yandex_code": "s9606393"},

{"name": "ШАНГАЛЫ", "code": 2010144, "yandex_code": "s9604068"},

{"name": "ПРЕДУГОЛЬН", "code": 2064552, "yandex_code": "s9613440"},

{"name": "ВОЗЫ", "code": 2000301, "yandex_code": "s9600967"},

{"name": "КОЛОНИЯ", "code": 2044693, "yandex_code": "s9610377"},

{"name": "ИМ ПЕСКОВА", "code": 2014234, "yandex_code": "s9604896"},

{"name": "ОП 14 КМ", "code": 2014236, "yandex_code": "s9604898"},

{"name": "ХАВА", "code": 2014491, "yandex_code": "s9605138"},

{"name": "АПКАН", "code": 2034073, "yandex_code": "s9887182"},

{"name": "ПЕРЕЛЕСКИ", "code": 2708789, "yandex_code": "s9619839"},

{"name": "ДЕНИСОВКА", "code": 2708788, "yandex_code": "s9619838"},

{"name": "ЖИТИКАРА", "code": 2708786, "yandex_code": "s9619836"},

{"name": "МУРСАЛИМК", "code": 2040445, "yandex_code": "s9609413"},

{"name": "КАЗАНКАН", "code": 2054008, "yandex_code": "s9611358"},

{"name": "ЧЕМИТОКВАД", "code": 2065153, "yandex_code": "s9613780"},

{"name": "ЯКОРН ЩЕЛЬ", "code": 2064093, "yandex_code": "s9613005"},

{"name": "РОХАТЫ", "code": 6600330, "yandex_code": "s9621877"},

{"name": "ВАХДАТ", "code": 6600220, "yandex_code": "s9621874"},

{"name": "ЯВАН", "code": 6600386, "yandex_code": "s9621884"},

{"name": "ВАХШ", "code": 6600385, "yandex_code": "s9621883"},

{"name": "ХАТЛОН", "code": 6600185, "yandex_code": "s9621868"},

{"name": "САНГТУДА", "code": 6600186, "yandex_code": "s9621869"},

{"name": "ДАНГАРА", "code": 6600187, "yandex_code": "s9621870"},

{"name": "КУЛЯБ", "code": 6600188, "yandex_code": "s9621871"},

{"name": "КАЗАРИНОВО", "code": 2010277, "yandex_code": "s9639394"},

{"name": "НОВ ЗАИМКА", "code": 2030266, "yandex_code": "s9607665"},

{"name": "МОХОВАЯ П", "code": 2050131, "yandex_code": "s9610990"},

{"name": "ОП 333 КМ", "code": 2700284, "yandex_code": "s9872462"},

{"name": "ОП 428 КМ", "code": 2700189, "yandex_code": "s9871594"},

{"name": "АЭРОПОРТ", "code": 2005099, "yandex_code": "s9603494"},

{"name": "ПЕТРОЗАВ Т2", "code": 2005621, "yandex_code": "s9887141"},

{"name": "ЛЕБЕДЯНЬ", "code": 2015350, "yandex_code": "s9605461"},

{"name": "Л ТОЛСТОЙ", "code": 2015355, "yandex_code": "s9605466"},

{"name": "КОТЛ-УЗЛ-П 2", "code": 2011297, "yandex_code": "s9604668"},

{"name": "ЧУДОВО НОВ", "code": 2005467, "yandex_code": "s9603844"},

{"name": "МЕНДЕЛЕЕВСКА", "code": 2005560, "yandex_code": "s9633732"},

{"name": "РЗД N6", "code": 2041533, "yandex_code": "s9609787"},

{"name": "РЗД N3", "code": 2041529, "yandex_code": "s9609783"},

{"name": "БЕКАБАД", "code": 2900772, "yandex_code": "s9620658"},

{"name": "ТЕРМЕЗ", "code": 2900255, "yandex_code": "s9620494"},

{"name": "ДЖАРКУРГАН", "code": 2900862, "yandex_code": "s9620721"},

{"name": "КУМКУРГАН", "code": 2900864, "yandex_code": "s9620723"},

{"name": "БОЙСУН", "code": 2900872, "yandex_code": "s9635346"},

{"name": "ДАРБАНД", "code": 2900871, "yandex_code": "s9635345"},

{"name": "ОКРАБОТ", "code": 2900861, "yandex_code": "s9635343"},

{"name": "ДЕХКОНОБОД", "code": 2900855, "yandex_code": "s9635341"},

{"name": "ПОЛЕВСКОЙ", "code": 2030071, "yandex_code": "s9607474"},

{"name": "КАРБЫШЕВ 1", "code": 2044830, "yandex_code": "s9610513"},

{"name": "ГРОМОВО", "code": 2024637, "yandex_code": "s9606401"},

{"name": "ТРОФИМОВ 1", "code": 2020065, "yandex_code": "s9605624"},

{"name": "САРАТОВ 2Т", "code": 2021070, "yandex_code": "s9606069"},

{"name": "ИЛОВЛЯ 2", "code": 2020819, "yandex_code": "s9605863"},

{"name": "КОЛОЦКИЙ", "code": 2020023, "yandex_code": "s9605582"},

{"name": "ЭНЕМ 1", "code": 2064189, "yandex_code": "s9613092"},

{"name": "ЮРОВСКИЙ", "code": 2064168, "yandex_code": "s9613071"},

{"name": "РОССОША", "code": 2020804, "yandex_code": "s9605848"},

{"name": "ХАСЛЯТ", "code": 2031481, "yandex_code": "s9634502"},

{"name": "АНГРЕН", "code": 2900106, "yandex_code": "s9881808"},

{"name": "ЯНГИЮЛЬ", "code": 2900753, "yandex_code": "s9620646"},

{"name": "НУКУС ПРЕД К", "code": 2902205, "yandex_code": "s9620766"},

{"name": "Бабушкино", "code": 2030203, "yandex_code": "s9607604"},

{"name": "Смычка", "code": 2030124, "yandex_code": "s9607527"},

{"name": "ЗЛИНО", "code": 2005103, "yandex_code": "s9603498"},

{"name": "Леонидовка", "code": 2024584, "yandex_code": "s9606348"},

{"name": "ЭНЕМ 2", "code": 2064714, "yandex_code": "s9613530"},

{"name": "Тёплый Ключ (39 км)", "code": 2031349, "yandex_code": "s9608224"},

{"name": "Большая Трифоновка", "code": 2031354, "yandex_code": "s9608229"},

{"name": "Алапаевск", "code": 2030160, "yandex_code": "s9607563"},

{"name": "ВИНЗИЛИ", "code": 2030261, "yandex_code": "s9607661"},

{"name": "Боровая", "code": 2004801, "yandex_code": "s9633753"},

{"name": "Мелеуз", "code": 2024772, "yandex_code": "s9606535"},

{"name": "Зирган", "code": 2024769, "yandex_code": "s9606532"},

{"name": "321 км", "code": 2044274, "yandex_code": "s9610310"},

{"name": "Тесна", "code": 2000154, "yandex_code": "s9600820"},

{"name": "Кашира", "code": 2000220, "yandex_code": "s9600886"},

{"name": "Жилёво", "code": 2000028, "yandex_code": "s9600694"},

{"name": "Михнево", "code": 2000083, "yandex_code": "s9600749"},

{"name": "Барыбино", "code": 2000675, "yandex_code": "s9601332"},

{"name": "Бирюлёво-Пасс.", "code": 2001110, "yandex_code": "s9601703"},

{"name": "Нагатинская", "code": 2001750, "yandex_code": "s9601975"},

{"name": "Верхние Котлы (Павелецкое направление)", "code": 2001011, "yandex_code": "s9868808"},

{"name": "РУДНЯНСКИЙ", "code": 2100419, "yandex_code": "s9614354"},

{"name": "САДКИ", "code": 2100627, "yandex_code": "s9614562"},

{"name": "КАЦУРЫ", "code": 2100197, "yandex_code": "s9614185"},

{"name": "Галачинский", "code": 2054851, "yandex_code": "s9611788"},

{"name": "Братское Море", "code": 2054852, "yandex_code": "s9611789"},

{"name": "БУГАЧ", "code": 2038003, "yandex_code": "s9608689"},

{"name": "ХОВРИНО", "code": 2006100, "yandex_code": "s9603877"},

{"name": "КАЛАКАЧАН", "code": 2055009, "yandex_code": "s9751395"},

{"name": "ДАБАН", "code": 2054026, "yandex_code": "s9611376"},

{"name": "ПУСТОШКА", "code": 2004512, "yandex_code": "s9603005"},

{"name": "ИДРИЦА", "code": 2004514, "yandex_code": "s9603007"},

{"name": "СЕБЕЖ", "code": 2004515, "yandex_code": "s9603008"},

{"name": "Власиха", "code": 2044828, "yandex_code": "s9610511"},

{"name": "325 км", "code": 2044290, "yandex_code": "s9610326"},

{"name": "Интернат", "code": 2045891, "yandex_code": "s9610724"},

{"name": "317 км", "code": 2044291, "yandex_code": "s9610327"},

{"name": "316 км", "code": 2044292, "yandex_code": "s9610328"},

{"name": "313 км", "code": 2044293, "yandex_code": "s9610329"},

{"name": "310 км", "code": 2044294, "yandex_code": "s9610330"},

{"name": "305 км", "code": 2044902, "yandex_code": "s9610580"},

{"name": "298 км", "code": 2044295, "yandex_code": "s9610331"},

{"name": "296 км", "code": 2044901, "yandex_code": "s9610579"},

{"name": "287 км", "code": 2044296, "yandex_code": "s9610332"},

{"name": "272 км", "code": 2044297, "yandex_code": "s9610333"},

{"name": "266 км", "code": 2044298, "yandex_code": "s9610334"},

{"name": "259 км", "code": 2044299, "yandex_code": "s9610335"},

{"name": "Дальний", "code": 2044835, "yandex_code": "s9610518"},

{"name": "245 км", "code": 2044655, "yandex_code": "s9610341"},

{"name": "МАЕВО", "code": 2004509, "yandex_code": "s9603002"},

{"name": "ЗАБЕЛЬЕ", "code": 2004511, "yandex_code": "s9603004"},

{"name": "НАЩЕКИНО", "code": 2004513, "yandex_code": "s9603006"},

{"name": "СПБ ДВОРЦ ПР", "code": 2005623, "yandex_code": "s9888863"},

{"name": "ДУБИНОВКА", "code": 2040362, "yandex_code": "s9609331"},

{"name": "Чернышевка", "code": 2034686, "yandex_code": "s9608590"},

{"name": "120 км", "code": 2024825, "yandex_code": "s9606583"},

{"name": "114 км", "code": 2025950, "yandex_code": "s9606905"},

{"name": "Земетчино", "code": 2024568, "yandex_code": "s9606332"},

{"name": "Морсово", "code": 2024082, "yandex_code": "s9606178"},

{"name": "Пашково", "code": 2024210, "yandex_code": "s9606256"},

{"name": "1478 км", "code": 2060979, "yandex_code": "s9612632"},

{"name": "Афанасьевский", "code": 2060557, "yandex_code": "s9612360"},

{"name": "Ключевая", "code": 2060558, "yandex_code": "s9612361"},

{"name": "МУРМАНСК Т", "code": 2005622, "yandex_code": "s9887155"},

{"name": "АПАТИТЫ ТУР", "code": 2004233, "yandex_code": "s9887156"},

{"name": "ВЕЛИЧКОВКА", "code": 2064161, "yandex_code": "s9613065"},

{"name": "БАГАЕВКА", "code": 2020798, "yandex_code": "s9605842"},

{"name": "МАРИТУЙ", "code": 2054655, "yandex_code": "s9611653"},

{"name": "ШАРЫЖАЛГАЙ", "code": 2054654, "yandex_code": "s9611652"},

{"name": "2347 км", "code": 2040779, "yandex_code": "s9609744"},

{"name": "Победим", "code": 2044862, "yandex_code": "s9610545"},

{"name": "347 км", "code": 2044278, "yandex_code": "s9610314"},

{"name": "Восток", "code": 2025908, "yandex_code": "s9606864"},

{"name": "СПБ МЕДН ВС", "code": 2005624, "yandex_code": "s9888867"},

{"name": "ПР РЕК И КАН", "code": 2005625, "yandex_code": "s9888868"},

{"name": "РОМАНТИК", "code": 2064680, "yandex_code": "s9881286"},

{"name": "АРХЫЗ", "code": 2064678, "yandex_code": "s9846226"},

{"name": "ЧЕРКЕССК", "code": 2064085, "yandex_code": "s9612997"},

{"name": "245 км", "code": 2003951, "yandex_code": "s9602451"},

{"name": "ХАТЕП", "code": 2900888, "yandex_code": "s9620736"},

{"name": "173 км", "code": 2060943, "yandex_code": "s9612603"},

{"name": "ТЫРНЫАУЗ", "code": 2064698, "yandex_code": "s9655143"},

{"name": "Пионерская", "code": 2030668, "yandex_code": "s9607994"},

{"name": "УЛАНОВО", "code": 2054656, "yandex_code": "s9611654"},

{"name": "ИМ.МОРОЗОВА", "code": 2004234, "yandex_code": "s9602600"},

{"name": "Площадь трёх вокзалов (Каланчёвская)", "code": 2001210, "yandex_code": "s2001005"},

{"name": "Серп и Молот", "code": 2001230, "yandex_code": "s9601796"},

{"name": "Чухлинка", "code": 2001310, "yandex_code": "s9601856"},

{"name": "Кусково", "code": 2001066, "yandex_code": "s9601671"},

{"name": "Новогиреево", "code": 2001150, "yandex_code": "s9601737"},

{"name": "Реутов", "code": 2001036, "yandex_code": "s9600796"},

{"name": "Кивер", "code": 2010123, "yandex_code": "s9604047"},

{"name": "ГНИЛОВСКАЯ", "code": 2064576, "yandex_code": "s9613463"},

{"name": "Южная", "code": 2054883, "yandex_code": "s9611820"},

{"name": "Иркутный мост", "code": 2054891, "yandex_code": "s9611827"},

{"name": "Мельниково", "code": 2054893, "yandex_code": "s9611829"},

{"name": "Реттиховка", "code": 2034683, "yandex_code": "s9608587"},

{"name": "ШУРЧИ", "code": 2900866, "yandex_code": "s9620725"},

{"name": "ДЕНАУ", "code": 2900780, "yandex_code": "s9620666"},

{"name": "САРЫАСИЯ", "code": 2900868, "yandex_code": "s9620727"},

{"name": "МСТИН МОСТ", "code": 2004589, "yandex_code": "s9603082"},

{"name": "Отрожка", "code": 2014478, "yandex_code": "s9605129"},

{"name": "Геолог", "code": 2100812, "yandex_code": "s9614747"},

{"name": "Избынь", "code": 2100427, "yandex_code": "s9614362"},

{"name": "КЫЗГАЛДАКТ", "code": 2704842, "yandex_code": "s9619379"},

{"name": "КУРАЙЛЫ", "code": 2704841, "yandex_code": "s9619378"},

{"name": "РЗД N38", "code": 2705792, "yandex_code": "s9619531"},

{"name": "КАМЫССАЙ", "code": 2704839, "yandex_code": "s9619376"},

{"name": "РЗД 36 КМ", "code": 2700168, "yandex_code": "s9872451"},

{"name": "ЖАМАНСУ", "code": 2704836, "yandex_code": "s9619373"},

{"name": "ШАРУА", "code": 2704835, "yandex_code": "s9619372"},

{"name": "РЗД N33", "code": 2705791, "yandex_code": "s9619530"},

{"name": "ОП 1473 КМ", "code": 2704976, "yandex_code": "s9619508"},

{"name": "ТУЗОВО", "code": 2704975, "yandex_code": "s9619507"},

{"name": "СУЛУСАЙ", "code": 2704971, "yandex_code": "s9619503"},

{"name": "ЖАРКИЙ", "code": 2704787, "yandex_code": "s9619324"},

{"name": "ПЕПЕЛ", "code": 2704969, "yandex_code": "s9619501"},

{"name": "ГУГНЯ", "code": 2704968, "yandex_code": "s9619500"},

{"name": "АНГАТЫ", "code": 2704967, "yandex_code": "s9619499"},

{"name": "ТАКСАЙ", "code": 2704965, "yandex_code": "s9619497"},

{"name": "ОП 1338 КМ", "code": 2704964, "yandex_code": "s9619496"},

{"name": "КОЛУЗАНОВО", "code": 2704962, "yandex_code": "s9619494"},

{"name": "ЖИЛАЕВО", "code": 2704785, "yandex_code": "s9619322"},

{"name": "Победа", "code": 2001119, "yandex_code": "s9601710"},

{"name": "Крёкшино", "code": 2002088, "yandex_code": "s9602221"},

{"name": "Санино", "code": 2001681, "yandex_code": "s9874719"},

{"name": "Кокошкино", "code": 2000525, "yandex_code": "s9601187"},

{"name": "Толстопальцево", "code": 2000036, "yandex_code": "s9600702"},

{"name": "Ольгино", "code": 2001185, "yandex_code": "s9881697"},

{"name": "Еремино", "code": 2100248, "yandex_code": "s9614236"},

{"name": "Калининский", "code": 2100342, "yandex_code": "s9614328"},

{"name": "Тихиничи", "code": 2100341, "yandex_code": "s9614327"},

{"name": "Зелёный остров", "code": 2100340, "yandex_code": "s9614326"},

{"name": "Буслы", "code": 2100583, "yandex_code": "s9614518"},

{"name": "Радеево", "code": 2100572, "yandex_code": "s9614507"},

{"name": "Череток", "code": 2100339, "yandex_code": "s9614325"},

{"name": "Бушевка", "code": 2100338, "yandex_code": "s9614324"},

{"name": "Потаповка", "code": 2100337, "yandex_code": "s9614323"},

{"name": "Качаново", "code": 2100336, "yandex_code": "s9614322"},

{"name": "Вирский", "code": 2100223, "yandex_code": "s9614211"},

{"name": "Металлург", "code": 2100849, "yandex_code": "s9614784"},

{"name": "Калыбовка", "code": 2100334, "yandex_code": "s9614320"},

{"name": "Крутиха", "code": 2030212, "yandex_code": "s9607613"},

{"name": "Ронаёль", "code": 2011204, "yandex_code": "s9604586"},

{"name": "ШИПЕЛОВО", "code": 2030553, "yandex_code": "s9607893"},

{"name": "ЖАСЛЫК", "code": 2900740, "yandex_code": "s9620634"},

{"name": "КАШКАДАРЬЯ", "code": 2900792, "yandex_code": "s9620677"},

{"name": "ПАХТААБАД", "code": 6600869, "yandex_code": "s9621891"},

{"name": "МОСКВА ПАВ Т", "code": 2001126, "yandex_code": "s9885900"},

{"name": "АРАМИЛЬ", "code": 2030059, "yandex_code": "s9607462"},

{"name": "101 км", "code": 2031243, "yandex_code": "s9608118"},

{"name": "ПРИМЫКАНИЕ", "code": 2020797, "yandex_code": "s9605841"},

{"name": "УКТУС", "code": 2030056, "yandex_code": "s9607459"},

{"name": "ТАЙБОЛА", "code": 2004397, "yandex_code": "s9602890"},

{"name": "Позимь", "code": 2061439, "yandex_code": "s9612689"},

{"name": "Июль", "code": 2061566, "yandex_code": "s9612780"},

{"name": "Болгуры", "code": 2061613, "yandex_code": "s9612821"},

{"name": "Оленевод", "code": 2035637, "yandex_code": "s9608671"},

{"name": "472 км", "code": 2030378, "yandex_code": "s9607752"},

{"name": "2575 км", "code": 2044151, "yandex_code": "s9610237"},

{"name": "Кочковатский", "code": 2044675, "yandex_code": "s9610359"},

{"name": "Вожой", "code": 2061616, "yandex_code": "s9612824"},

{"name": "2595 км", "code": 2044152, "yandex_code": "s9610238"},

{"name": "Могилёв-2", "code": 2100298, "yandex_code": "s9614285"},

{"name": "Тишовка", "code": 2100884, "yandex_code": "s9614819"},

{"name": "Голынец", "code": 2100526, "yandex_code": "s9889268"},

{"name": "Понизов", "code": 2100791, "yandex_code": "s9614726"},

{"name": "Вендриж", "code": 2100081, "yandex_code": "s9614069"},

{"name": "Хоново", "code": 2100790, "yandex_code": "s9614725"},

{"name": "Новая Нива", "code": 2100883, "yandex_code": "s9614818"},

{"name": "Семуковичи", "code": 2100525, "yandex_code": "s9614460"},

{"name": "Синюга", "code": 2100789, "yandex_code": "s9614724"},

{"name": "Милое", "code": 2100078, "yandex_code": "s9614066"},

{"name": "Заличинка", "code": 2100928, "yandex_code": "s9623142"},

{"name": "Развадово", "code": 2100788, "yandex_code": "s9614723"},

{"name": "Воничи", "code": 2100077, "yandex_code": "s9614065"},

{"name": "Суша", "code": 2100787, "yandex_code": "s9614722"},

{"name": "Стоялово", "code": 2100524, "yandex_code": "s9614459"},

{"name": "Ольховка", "code": 2100882, "yandex_code": "s9614817"},

{"name": "Тачанка", "code": 2100074, "yandex_code": "s9614062"},

{"name": "Брицаловичи", "code": 2100072, "yandex_code": "s9614060"},

{"name": "Горожа", "code": 2100881, "yandex_code": "s9614816"},

{"name": "Осиповичи-2", "code": 2100271, "yandex_code": "s9614259"},

{"name": "ЛУЧЕВОЙ", "code": 2004331, "yandex_code": "s9602824"},

{"name": "МУР ВОРОТА", "code": 2004217, "yandex_code": "s9602710"},

{"name": "КРАСНОСЕЛ", "code": 2060764, "yandex_code": "s9612546"},

{"name": "Аять", "code": 2030051, "yandex_code": "s9607454"},

{"name": "Монетная", "code": 2030215, "yandex_code": "s9607616"},

{"name": "Копалуха", "code": 2030214, "yandex_code": "s9607615"},

{"name": "Адуй", "code": 2030213, "yandex_code": "s9607614"},

{"name": "Стриганово", "code": 2030541, "yandex_code": "s9607882"},

{"name": "Таватуй", "code": 2030049, "yandex_code": "s9607452"},

{"name": "Зыряновский ( бывш. 132 км)", "code": 2030540, "yandex_code": "s9607881"},

{"name": "Коптелово", "code": 2030205, "yandex_code": "s9607606"},

{"name": "Катышка", "code": 2031359, "yandex_code": "s9608234"},

{"name": "Самоцвет", "code": 2030206, "yandex_code": "s9607607"},

{"name": "Незевай", "code": 2030207, "yandex_code": "s9607608"},

{"name": "Красные Орлы", "code": 2030208, "yandex_code": "s9607609"},

{"name": "Синие Камни", "code": 2030338, "yandex_code": "s9886624"},

{"name": "Аппаратная", "code": 2030054, "yandex_code": "s9607457"},

{"name": "Березит", "code": 2030216, "yandex_code": "s9607617"},

{"name": "Кедровка", "code": 2030292, "yandex_code": "s9607688"},

{"name": "Костоусово", "code": 2030211, "yandex_code": "s9607612"},

{"name": "Никель", "code": 2031350, "yandex_code": "s9608225"},

{"name": "Соснята", "code": 2031351, "yandex_code": "s9608226"},

{"name": "Бурлаки", "code": 2031352, "yandex_code": "s9608227"},

{"name": "Лисава", "code": 2031357, "yandex_code": "s9608232"},

{"name": "739 км", "code": 2026031, "yandex_code": "s9606964"},

{"name": "Переделкино", "code": 2001795, "yandex_code": "s9602014"},

{"name": "Седьвож", "code": 2010091, "yandex_code": "s9604019"},

{"name": "Улановая", "code": 2038309, "yandex_code": "s9608979"},

{"name": "ЧИНАЗ", "code": 2900756, "yandex_code": "s9620649"},

{"name": "РЕМОВСКАЯ", "code": 2700671, "yandex_code": "s9619093"},

{"name": "НЕВЕРОВСК", "code": 2700672, "yandex_code": "s9619094"},

{"name": "МАСАЛЬСКАЯ", "code": 2700673, "yandex_code": "s9619095"},

{"name": "ТРЕТЬЯКОВО", "code": 2700993, "yandex_code": "s9619300"},

{"name": "ШЕМОНАИХА", "code": 2700910, "yandex_code": "s9619289"},

{"name": "У ТАЛОВКА", "code": 2700676, "yandex_code": "s9619097"},

{"name": "РУЛИХА", "code": 2700677, "yandex_code": "s9619098"},

{"name": "ФЕСТИВАЛЬН", "code": 2700679, "yandex_code": "s9619099"},

{"name": "ОП 167 КМ", "code": 2700681, "yandex_code": "s9619101"},

{"name": "ОП 174 КМ", "code": 2700242, "yandex_code": "s9871545"},

{"name": "ПРЕДГОРНАЯ", "code": 2700682, "yandex_code": "s9619102"},

{"name": "ОП 195 КМ", "code": 2700243, "yandex_code": "s9871547"},

{"name": "ИРТЫШЗАВОД", "code": 2700683, "yandex_code": "s9619103"},

{"name": "КАЗИЕВКА", "code": 2700418, "yandex_code": "s9619073"},

{"name": "КОРШУНОВО", "code": 2700909, "yandex_code": "s9619288"},

{"name": "ОП 259 КМ", "code": 2700695, "yandex_code": "s9619113"},

{"name": "ОП 265 КМ", "code": 2700412, "yandex_code": "s9619067"},

{"name": "ОП 275 КМ", "code": 2700228, "yandex_code": "s9872308"},

{"name": "ЧЕРЕМШАНКА", "code": 2700696, "yandex_code": "s9619114"},

{"name": "ОП 295 КМ", "code": 2700248, "yandex_code": "s9871549"},

{"name": "ОП 307 КМ", "code": 2700430, "yandex_code": "s9619084"},

{"name": "ОП 313 КМ", "code": 2700697, "yandex_code": "s9619115"},

{"name": "ТИШИНСКАЯ", "code": 2700919, "yandex_code": "s9619298"},

{"name": "ОП 331 КМ", "code": 2700250, "yandex_code": "s9871550"},

{"name": "РИДДЕР", "code": 2700840, "yandex_code": "s9619237"},

{"name": "БОГАНДИНСК", "code": 2030262, "yandex_code": "s9607662"},

{"name": "ДУПЛЕНСКАЯ", "code": 2044768, "yandex_code": "s9610451"},

{"name": "1811 км", "code": 2010934, "yandex_code": "s9604499"},

{"name": "ПЕТРОВСК С", "code": 2020810, "yandex_code": "s9605854"},

{"name": "ПОДСНЕЖНАЯ", "code": 2020777, "yandex_code": "s9605821"},

{"name": "БУРАСЫ", "code": 2020778, "yandex_code": "s9605822"},

{"name": "КАРАБУЛАК", "code": 2020779, "yandex_code": "s9605823"},

{"name": "СЕРОВ СОРТ", "code": 2030520, "yandex_code": "s9607862"},

{"name": "ДИШНЯ", "code": 2000756, "yandex_code": "s9601392"},

{"name": "КИМОСОЗЕРО", "code": 2005108, "yandex_code": "s9603503"},

{"name": "МОРДВЕС", "code": 2000122, "yandex_code": "s9600788"},

{"name": "ГРИЦОВО", "code": 2000127, "yandex_code": "s9600793"},

{"name": "МАКЛЕЦ", "code": 2000128, "yandex_code": "s9600794"},

{"name": "ТОВАРКОВО", "code": 2000133, "yandex_code": "s9600799"},

{"name": "Пожарский", "code": 2034492, "yandex_code": "s9608449"},

{"name": "ЛОКОТЬ ГР", "code": 2700740, "yandex_code": "s9619152"},

{"name": "ГОРЮЧКА", "code": 2020008, "yandex_code": "s9605567"},

{"name": "КЧЕРЫ", "code": 2004609, "yandex_code": "s9603102"},

{"name": "474 км", "code": 2001899, "yandex_code": "s9602081"},

{"name": "487 км", "code": 2002924, "yandex_code": "s9602267"},

{"name": "491 км", "code": 2001411, "yandex_code": "s9601935"},

{"name": "500 км", "code": 2002765, "yandex_code": "s9602243"},

{"name": "502 км", "code": 2001307, "yandex_code": "s9601853"},

{"name": "507 км", "code": 2002991, "yandex_code": "s9602312"},

{"name": "517 км", "code": 2001447, "yandex_code": "s9601966"},

{"name": "521 км", "code": 2002777, "yandex_code": "s9602244"},

{"name": "Букреевка", "code": 2000304, "yandex_code": "s9600970"},

{"name": "530 км", "code": 2001767, "yandex_code": "s9601989"},

{"name": "Светлая Жизнь", "code": 2000339, "yandex_code": "s9601005"},

{"name": "Стишь", "code": 2000224, "yandex_code": "s9600890"},

{"name": "Пилатовка", "code": 2000334, "yandex_code": "s9601000"},

{"name": "Становой Колодезь", "code": 2000226, "yandex_code": "s9600892"},

{"name": "406 км", "code": 2001715, "yandex_code": "s9655360"},

{"name": "408 км", "code": 2001272, "yandex_code": "s9601832"},

{"name": "Еропкино", "code": 2000227, "yandex_code": "s9600893"},

{"name": "Куракино", "code": 2000229, "yandex_code": "s9600895"},

{"name": "452 км", "code": 2002995, "yandex_code": "s9602316"},

{"name": "ПАША", "code": 2004676, "yandex_code": "s9603169"},

{"name": "ЭЛЬХОТОВО", "code": 2064046, "yandex_code": "s9612958"},

{"name": "Пенза-2", "code": 2024546, "yandex_code": "s9606310"},

{"name": "756 км", "code": 2025805, "yandex_code": "s9606772"},

{"name": "ГРОДЕКОВО", "code": 2034464, "yandex_code": "s9608423"},

{"name": "СУЙФЫНЬХЭ", "code": 3300410, "yandex_code": "s9620840"},

{"name": "ТАНГИМУШ", "code": 2900874, "yandex_code": "s9871419"},

{"name": "ЗАМЧАЛОВО", "code": 2064573, "yandex_code": "s9613460"},

{"name": "107 км", "code": 2030333, "yandex_code": "s9607720"},

{"name": "843 км", "code": 2010899, "yandex_code": "s9604464"},

{"name": "Храмцовская", "code": 2030238, "yandex_code": "s9607638"},

{"name": "Соцгород", "code": 2030687, "yandex_code": "s9608013"},

{"name": "Компрессорный Завод", "code": 2030681, "yandex_code": "s9608007"},

{"name": "Костариха", "code": 2060003, "yandex_code": "s9612091"},

{"name": "Сосьва", "code": 2031262, "yandex_code": "s9608137"},

{"name": "Мостозавод", "code": 2011233, "yandex_code": "s9604614"},

{"name": "Сухановка", "code": 2034506, "yandex_code": "s9608463"},

{"name": "Бубчиково", "code": 2030202, "yandex_code": "s9607603"},

{"name": "Мугайское", "code": 2030201, "yandex_code": "s9607602"},

{"name": "Ерзовка", "code": 2030199, "yandex_code": "s9607600"},

{"name": "Белая Глина", "code": 2031477, "yandex_code": "s9608292"},

{"name": "Предтурье", "code": 2030195, "yandex_code": "s9607596"},

{"name": "177 км", "code": 2031444, "yandex_code": "s9608242"},

{"name": "Синячиха", "code": 2030204, "yandex_code": "s9607605"},

{"name": "Верхняя Синячиха (226 км)", "code": 2031363, "yandex_code": "s9608238"},

{"name": "Сосьва-новая", "code": 2030193, "yandex_code": "s9607594"},

{"name": "СВЕТОТЕХН", "code": 2060576, "yandex_code": "s9612379"},

{"name": "БУИНСК", "code": 2060572, "yandex_code": "s9612375"},

{"name": "441 км", "code": 2042710, "yandex_code": "s9610113"},

{"name": "Начальное", "code": 2041669, "yandex_code": "s9609917"},

{"name": "436 км", "code": 2040744, "yandex_code": "s9609711"},

{"name": "Анненск", "code": 2040392, "yandex_code": "s9609361"},

{"name": "Запасное", "code": 2040393, "yandex_code": "s9609362"},

{"name": "Система", "code": 2043674, "yandex_code": "s9610146"},

{"name": "Джабык", "code": 2040394, "yandex_code": "s9609363"},

{"name": "Провалово", "code": 2034495, "yandex_code": "s9608452"},

{"name": "Киргишаны", "code": 2060005, "yandex_code": "s9612093"},

{"name": "Арсеньев", "code": 2034687, "yandex_code": "s9608591"},

{"name": "Казанское", "code": 2001211, "yandex_code": "s9601782"},

{"name": "Рязановка", "code": 2034498, "yandex_code": "s9608455"},

{"name": "Елюзань", "code": 2024589, "yandex_code": "s9606353"},

{"name": "300 км", "code": 2005533, "yandex_code": "s9635383"},

{"name": "Нарачино", "code": 2004680, "yandex_code": "s9603173"},

{"name": "Добывалово", "code": 2005106, "yandex_code": "s9603501"},

{"name": "Чернушки", "code": 2005107, "yandex_code": "s9603502"},

{"name": "361 км", "code": 2004109, "yandex_code": "s9635384"},

{"name": "Дворец", "code": 2004595, "yandex_code": "s9603088"},

{"name": "380 км (Быльчино)", "code": 2004945, "yandex_code": "s9603346"},

{"name": "Любница", "code": 2004596, "yandex_code": "s9603089"},

{"name": "Заход", "code": 2004112, "yandex_code": "s9602605"},

{"name": "452 км", "code": 2005324, "yandex_code": "s9603712"},

{"name": "151 км", "code": 2034741, "yandex_code": "s9608642"},

{"name": "Новочугуевка", "code": 2034691, "yandex_code": "s9608595"},

{"name": "Новотроицкое (80 км)", "code": 2031489, "yandex_code": "s9608294"},

{"name": "Курганка", "code": 2040506, "yandex_code": "s9609474"},

{"name": "Варфоломеевка", "code": 2034680, "yandex_code": "s9608584"},

{"name": "Сысоевка", "code": 2034688, "yandex_code": "s9608592"},

{"name": "Минск-Южный", "code": 2100004, "yandex_code": "s9613992"},

{"name": "Лошица", "code": 2100800, "yandex_code": "s9614735"},

{"name": "Железнодорожный", "code": 2100917, "yandex_code": "s9614851"},

{"name": "Колядичи", "code": 2100284, "yandex_code": "s9614272"},

{"name": "Мачулищи", "code": 2100318, "yandex_code": "s9614304"},

{"name": "Михановичи", "code": 2100111, "yandex_code": "s9614099"},

{"name": "Седча", "code": 2100630, "yandex_code": "s9614565"},

{"name": "Зазерка", "code": 2100320, "yandex_code": "s9614306"},

{"name": "Рыбцы", "code": 2100321, "yandex_code": "s9614307"},

{"name": "Равнополье", "code": 2100322, "yandex_code": "s9614308"},

{"name": "Руденск", "code": 2100671, "yandex_code": "s9614606"},

{"name": "Минск-Восточный", "code": 2101010, "yandex_code": "s9614870"},

{"name": "Тракторный", "code": 2100465, "yandex_code": "s9614400"},

{"name": "Степянка", "code": 2100101, "yandex_code": "s9614089"},

{"name": "Озерище", "code": 2100509, "yandex_code": "s9614444"},

{"name": "Колодищи", "code": 2100006, "yandex_code": "s9613994"},

{"name": "Садовый", "code": 2100464, "yandex_code": "s9614399"},

{"name": "Городище", "code": 2100510, "yandex_code": "s9614445"},

{"name": "Слобода", "code": 2100463, "yandex_code": "s9614398"},

{"name": "Красное Знамя", "code": 2100102, "yandex_code": "s9614090"},

{"name": "Заречное", "code": 2101015, "yandex_code": "s9614875"},

{"name": "Загорье", "code": 2100461, "yandex_code": "s9614396"},

{"name": "Домашаны", "code": 2101151, "yandex_code": "s9614914"},

{"name": "Лебяжий", "code": 2100865, "yandex_code": "s9614800"},

{"name": "Ждановичи", "code": 2100279, "yandex_code": "s9614267"},

{"name": "Баневурово", "code": 2034699, "yandex_code": "s9608603"},

{"name": "Виневитино", "code": 2034460, "yandex_code": "s9608419"},

{"name": "Барсовый", "code": 2035638, "yandex_code": "s9608672"},

{"name": "Приморская", "code": 2034494, "yandex_code": "s9608451"},

{"name": "Кедровой", "code": 2034473, "yandex_code": "s9608432"},

{"name": "Бамбурово", "code": 2034454, "yandex_code": "s9608413"},

{"name": "Махалино", "code": 2034485, "yandex_code": "s9608443"},

{"name": "246 км", "code": 2041459, "yandex_code": "s9609750"},

{"name": "Новая Заря", "code": 2030191, "yandex_code": "s9607593"},

{"name": "204 км", "code": 2001323, "yandex_code": "s9601865"},

{"name": "СЕЛЯТИНО", "code": 2000745, "yandex_code": "s9601383"},

{"name": "ТЮНТЮГУР", "code": 2708785, "yandex_code": "s9619835"},

{"name": "Юнг-Ях", "code": 2030306, "yandex_code": "s9607697"},

{"name": "Рычково", "code": 2031365, "yandex_code": "s9608240"},

{"name": "Винокурово", "code": 2031366, "yandex_code": "s9608241"},

{"name": "Карпунино", "code": 2030196, "yandex_code": "s9607597"},

{"name": "МАНУШКИНО", "code": 2005423, "yandex_code": "s9603808"},

{"name": "ЗИЛАЙ", "code": 2060508, "yandex_code": "s9612312"},

{"name": "Усть-Березовка", "code": 2030194, "yandex_code": "s9607595"},

{"name": "СЫРОПЯТСК", "code": 2044689, "yandex_code": "s9610373"},

{"name": "Шершни", "code": 2040466, "yandex_code": "s9609434"},

{"name": "Безменово", "code": 2044796, "yandex_code": "s9610479"},

{"name": "САМАРА ТУР", "code": 2024163, "yandex_code": "s9887137"},

{"name": "ТЮМЕНЬ ТУР", "code": 2030339, "yandex_code": "s9887136"},

{"name": "УЛЬГИ", "code": 2054005, "yandex_code": "s9611355"},

{"name": "Сетово", "code": 2030574, "yandex_code": "s9607912"},

{"name": "Томино", "code": 2040418, "yandex_code": "s9609386"},

{"name": "Южноуральск", "code": 2040777, "yandex_code": "s9609742"},

{"name": "Новый Петергоф", "code": 2006300, "yandex_code": "s9603887"},

{"name": "Минское Море", "code": 2100762, "yandex_code": "s9614697"},

{"name": "Лимонник", "code": 2034689, "yandex_code": "s9608593"},

{"name": "210 км (208 км)", "code": 2001308, "yandex_code": "s9601854"},

{"name": "Стенькино-1", "code": 2000048, "yandex_code": "s9600714"},

{"name": "Кадуй", "code": 2054373, "yandex_code": "s9611627"},

{"name": "Лесок", "code": 2000057, "yandex_code": "s9600723"},

{"name": "Покровский", "code": 2048121, "yandex_code": "s9610877"},

{"name": "Электродепо", "code": 2030659, "yandex_code": "s9607985"},

{"name": "ВОРОБЕЦКАЯ", "code": 2004040, "yandex_code": "s9602533"},

{"name": "ВЫДУМКА", "code": 2004042, "yandex_code": "s9602535"},

{"name": "БРЫКАНОВО", "code": 2004045, "yandex_code": "s9602538"},

{"name": "ЗАВАРУЙКА", "code": 2004046, "yandex_code": "s9602539"},

{"name": "ЧЕРЕМАС", "code": 2060063, "yandex_code": "s9612126"},

{"name": "СЕЧУГА", "code": 2060401, "yandex_code": "s9612206"},

{"name": "ШОНИХА", "code": 2060403, "yandex_code": "s9612208"},

{"name": "Ермолаево", "code": 2024774, "yandex_code": "s9606537"},

{"name": "Апрелевка", "code": 2000440, "yandex_code": "s9601102"},

{"name": "СП-ТОВ-ВИТЕБ", "code": 2004826, "yandex_code": "s9634525"},

{"name": "ПЕТРОВ ВАЛ", "code": 2020760, "yandex_code": "s9605804"},

{"name": "КУЛАТКА", "code": 2020938, "yandex_code": "s9605981"},

{"name": "ЖУТОВО", "code": 2020845, "yandex_code": "s9605889"},

{"name": "КОТЕЛЬНИК", "code": 2020860, "yandex_code": "s9605904"},

{"name": "ЗИМОВНИКИ", "code": 2064295, "yandex_code": "s9613195"},

{"name": "ТИХОРЕЦКАЯ", "code": 2064260, "yandex_code": "s9613161"},

{"name": "ВЫСЕЛКИ", "code": 2064197, "yandex_code": "s9613100"},

{"name": "КОРЕНОВСК", "code": 2064265, "yandex_code": "s9613166"},

{"name": "ДИНСКАЯ", "code": 2064193, "yandex_code": "s9613096"},

{"name": "ГОРЯЧИЙ КЛЮЧ", "code": 2064057, "yandex_code": "s9612969"},

{"name": "ТУАПСЕ ПАС", "code": 2064140, "yandex_code": "s9613044"},

{"name": "ЛАЗАРЕВСК", "code": 2064030, "yandex_code": "s9612942"},

{"name": "СОЧИ", "code": 2064130, "yandex_code": "s9613034"},

{"name": "ТЮМЕНЬ", "code": 2030100, "yandex_code": "s9607503"},

{"name": "СЛЮДЯНКА 1", "code": 2054320, "yandex_code": "s9611613"},

{"name": "МЫСОВАЯ", "code": 2054745, "yandex_code": "s9611695"},

{"name": "ЗАУДИНСКИЙ", "code": 2054552, "yandex_code": "s9611636"},

{"name": "ТИМЛЮЙ", "code": 2054560, "yandex_code": "s9611644"},

{"name": "ЗАИГРАЕВО", "code": 2054554, "yandex_code": "s9611638"},

{"name": "Жлобин-Подольский", "code": 2100533, "yandex_code": "s9614468"},

{"name": "Мелькомбинат", "code": 2100410, "yandex_code": "s9614345"},

{"name": "Шварц", "code": 2000508, "yandex_code": "s9634246"},

{"name": "Оболенское", "code": 2000146, "yandex_code": "s9600812"},

{"name": "Дедилово", "code": 2000147, "yandex_code": "s9600813"},

{"name": "Узловая-2", "code": 2000129, "yandex_code": "s9600795"},

{"name": "Руднево", "code": 2000300, "yandex_code": "s9600966"},

{"name": "23 км", "code": 2000598, "yandex_code": "s9601259"},

{"name": "Сборная-Угольная", "code": 2000267, "yandex_code": "s9600933"},

{"name": "Новомосковск-1", "code": 2000290, "yandex_code": "s9600956"},

{"name": "Урванка", "code": 2000266, "yandex_code": "s9600932"},

{"name": "Забитуй", "code": 2054324, "yandex_code": "s9611617"},

{"name": "ЗИМЕНКИ", "code": 2060438, "yandex_code": "s9612243"},

{"name": "Коркодин", "code": 2042587, "yandex_code": "s9610019"},

{"name": "Полдневая", "code": 2040486, "yandex_code": "s9609454"},

{"name": "ПСЫРЦХА", "code": 4200005, "yandex_code": "s9672528"},

{"name": "Мраморская", "code": 2030069, "yandex_code": "s9607472"},

{"name": "Ачинск-2", "code": 2038162, "yandex_code": "s9608840"},

{"name": "Пригородный", "code": 2038321, "yandex_code": "s9608991"},

{"name": "Жилмассив", "code": 2045790, "yandex_code": "s9610691"},

{"name": "161 км", "code": 2031358, "yandex_code": "s9608233"},

{"name": "Азарово", "code": 2000369, "yandex_code": "s9601032"},

{"name": "Кучино", "code": 2001190, "yandex_code": "s9601767"},

{"name": "Салтыковская", "code": 2001370, "yandex_code": "s9601902"},

{"name": "Никольское", "code": 2000515, "yandex_code": "s9601177"},

{"name": "КАРАЧАЕВСК", "code": 2064681, "yandex_code": "s9880539"},

{"name": "ТЕБЕРДА", "code": 2064699, "yandex_code": "s9883587"},

{"name": "ДОМБАЙ", "code": 2064683, "yandex_code": "s9840653"},

{"name": "Красноярка", "code": 2024041, "yandex_code": "s9606137"},

{"name": "Вассиановка", "code": 2034682, "yandex_code": "s9608586"},

{"name": "Туринский", "code": 2030568, "yandex_code": "s9607906"},

{"name": "ЗЕЛЕНЧУКСКАЯ", "code": 2064701, "yandex_code": "s9637211"},

{"name": "БЕЗВЕРХОВ ПВ", "code": 2034065, "yandex_code": "s9879259"},

{"name": "СЛАВЯНКА АВТ", "code": 2034066, "yandex_code": "s9776211"},

{"name": "РЯЗАНОВКА ПВ", "code": 2034068, "yandex_code": "s9879262"},

{"name": "АНДРЕЕВКА Л", "code": 2034069, "yandex_code": "s9879264"},

{"name": "АНДРЕЕВКА О", "code": 2034070, "yandex_code": "s9879265"},

{"name": "АНДРЕЕВКА В", "code": 2034071, "yandex_code": "s9879266"},

{"name": "Дунаево (3 км)", "code": 2051459, "yandex_code": "s9611326"},

{"name": "Алнаши", "code": 2060369, "yandex_code": "s9612175"},

{"name": "РЗД НР 3", "code": 2700093, "yandex_code": "s9851640"},

{"name": "РЗД НР 4", "code": 2700095, "yandex_code": "s9851641"},

{"name": "РЗД НР 6", "code": 2700119, "yandex_code": "s9851644"},

{"name": "РЗД НР 7", "code": 2700122, "yandex_code": "s9851646"},

{"name": "Терси", "code": 2060358, "yandex_code": "s9612164"},

{"name": "Набережный (1809 км)", "code": 2010933, "yandex_code": "s9604498"},

{"name": "Сияние Севера (1815 км)", "code": 2010936, "yandex_code": "s9604501"},

{"name": "Прудской (257 км)", "code": 2044267, "yandex_code": "s9610303"},

{"name": "ИГЛИНО", "code": 2024743, "yandex_code": "s9606506"},

{"name": "Екатериновка (2798 км)", "code": 2044165, "yandex_code": "s9610251"},

{"name": "Моргородок", "code": 2034524, "yandex_code": "s9608481"},

{"name": "Бархатная", "code": 2034722, "yandex_code": "s9608624"},

{"name": "Ежиха", "code": 2060468, "yandex_code": "s9612272"},

{"name": "Тюмень-северная", "code": 2031418, "yandex_code": "s9633849"},

{"name": "1791 км", "code": 2011420, "yandex_code": "s9826078"},

{"name": "Бутырское", "code": 2040499, "yandex_code": "s9609467"},

{"name": "РЫЗДВЯНАЯ", "code": 2064233, "yandex_code": "s9613134"},

{"name": "Гостюхинский карьер (262 км)", "code": 2060797, "yandex_code": "s9612564"},

{"name": "Юдино (восточный парк)", "code": 9992445, "yandex_code": "s9657384"},

{"name": "Царицыно", "code": 2000225, "yandex_code": "s9600891"},

{"name": "Курьяново", "code": 2000214, "yandex_code": "s9876608"},

{"name": "Лозовый", "code": 2034710, "yandex_code": "s9608612"},

{"name": "КУЗИНО", "code": 2030039, "yandex_code": "s9607442"},

{"name": "САБИК", "code": 2030038, "yandex_code": "s9607441"},

{"name": "Войновка", "code": 2030258, "yandex_code": "s9607658"},

{"name": "Труд (298 км)", "code": 2044271, "yandex_code": "s9610307"},

{"name": "Каран-Елга", "code": 2024728, "yandex_code": "s9606491"},

{"name": "ОЗ АНДРЕЕВ", "code": 2030259, "yandex_code": "s9607659"},

{"name": "Буянки", "code": 2034684, "yandex_code": "s9608588"},

{"name": "Школьная", "code": 2024146, "yandex_code": "s9606242"},

{"name": "Дозмер", "code": 2011205, "yandex_code": "s9604587"},

{"name": "Дубининский", "code": 2034694, "yandex_code": "s9608598"},

{"name": "Путевая машинная станция (2888 км)", "code": 2044172, "yandex_code": "s9610258"},

{"name": "Алонский", "code": 2044168, "yandex_code": "s9610254"},

{"name": "Помурино", "code": 2044160, "yandex_code": "s9610246"},

{"name": "Буланаш", "code": 2030227, "yandex_code": "s9607627"},

{"name": "Сирень (228 км)", "code": 2030229, "yandex_code": "s9607629"},

{"name": "Кунара", "code": 2030231, "yandex_code": "s9607631"},

{"name": "Баженово", "code": 2030234, "yandex_code": "s9607634"},

{"name": "Мезенский", "code": 2030627, "yandex_code": "s9607955"},

{"name": "Гагарский", "code": 2030064, "yandex_code": "s9607467"},

{"name": "Косулино", "code": 2030063, "yandex_code": "s9607466"},

{"name": "Путевка", "code": 2030477, "yandex_code": "s9607827"},

{"name": "Исток", "code": 2030062, "yandex_code": "s9607465"},

{"name": "САЖНОЕ", "code": 2014384, "yandex_code": "s9605041"},

{"name": "Ачи", "code": 2064214, "yandex_code": "s9613116"},

{"name": "КОДАР", "code": 2054119, "yandex_code": "s9611469"},

{"name": "Чумпас", "code": 2030651, "yandex_code": "s9607977"},

{"name": "ОСТАНИНО", "code": 2078732, "yandex_code": "s9616985"},

{"name": "Байдук", "code": 2011193, "yandex_code": "s9604576"},

{"name": "Турун", "code": 2011238, "yandex_code": "s9604619"},

{"name": "ГУДОГАЙ", "code": 2100024, "yandex_code": "s9614012"},

{"name": "Вылью", "code": 2010200, "yandex_code": "s9604123"},

{"name": "Джинтуй", "code": 2011198, "yandex_code": "s9604581"},

{"name": "Охотпост", "code": 2011196, "yandex_code": "s9604579"},

{"name": "Калякурья", "code": 2010341, "yandex_code": "s9604261"},

{"name": "Кожим Рудник (1952 км)", "code": 2010360, "yandex_code": "s9604277"},

{"name": "Чёрный", "code": 2011241, "yandex_code": "s9604622"},

{"name": "Угольный", "code": 2011195, "yandex_code": "s9604578"},

{"name": "Лудзя", "code": 2061441, "yandex_code": "s9612691"},

{"name": "Дагестанские Огни", "code": 2064222, "yandex_code": "s9613124"},

{"name": "Новоиерусалимская", "code": 2000560, "yandex_code": "s9601222"},

{"name": "Нахабино", "code": 2000460, "yandex_code": "s9601122"},

{"name": "Рижская (МЦД-2, 4)", "code": 2000605, "yandex_code": "s9601266"},

{"name": "Волоколамская", "code": 2001102, "yandex_code": "s9874963"},

{"name": "50 км", "code": 2001824, "yandex_code": "s9602027"},

{"name": "Холщёвики", "code": 2002078, "yandex_code": "s9602215"},

{"name": "Завод Стекловолокно (2304 км)", "code": 2064529, "yandex_code": "s9613417"},

{"name": "Сысерть", "code": 2030057, "yandex_code": "s9607460"},

{"name": "Безымянка", "code": 2024620, "yandex_code": "s9606384"},

{"name": "Пятилетка", "code": 2025790, "yandex_code": "s9606758"},

{"name": "Ленинская", "code": 2001830, "yandex_code": "s9602030"},

{"name": "МУЛЯНКА", "code": 2030024, "yandex_code": "s9607428"},

{"name": "ХРУСТАЛЬН", "code": 2030047, "yandex_code": "s9607450"},

{"name": "КУНАШАК", "code": 2040488, "yandex_code": "s9609456"},

{"name": "Заольша", "code": 2100492, "yandex_code": "s9614427"},

{"name": "Арбузовка", "code": 2044833, "yandex_code": "s9610516"},

{"name": "Краснозаринск", "code": 2061553, "yandex_code": "s9612772"},

{"name": "Дачная (771 км)", "code": 2060958, "yandex_code": "s9612617"},

{"name": "ЖЕТИТОБЕ", "code": 2705832, "yandex_code": "s9619558"},

{"name": "Лебяжье", "code": 2044264, "yandex_code": "s9610300"},

{"name": "ВОРОНЕЖ К", "code": 2014002, "yandex_code": "s2014002"},

{"name": "Первая Речка", "code": 2035580, "yandex_code": "s9608653"},

{"name": "Нижневартовск-2", "code": 2030653, "yandex_code": "s9607979"},

{"name": "Силинский", "code": 2030555, "yandex_code": "s9607895"},

{"name": "МОЗЫРЬ", "code": 2100254, "yandex_code": "s9614242"},

{"name": "Совхоз Бердский", "code": 2044260, "yandex_code": "s9610296"},

{"name": "Морозово", "code": 2044257, "yandex_code": "s9610293"},

{"name": "Пикетное", "code": 2044685, "yandex_code": "s9610369"},

{"name": "КУЗНЕЧНАЯ", "code": 2044998, "yandex_code": "s9804181"},

{"name": "Шиловский", "code": 2044859, "yandex_code": "s9610542"},

{"name": "Зимари", "code": 2044268, "yandex_code": "s9610304"},

{"name": "Ядринцево", "code": 2045751, "yandex_code": "s9610680"},

{"name": "Степное (290 км)", "code": 2044270, "yandex_code": "s9610306"},

{"name": "Алтай", "code": 2044269, "yandex_code": "s9610305"},

{"name": "Ползуново", "code": 2044885, "yandex_code": "s9610568"}]';



clearDateBase($link,$arr);

function clearDateBase($link, $array){
//echo $array;
$codeYandexOr = "";
$codeOr = "";
$obj = json_decode($array, true);
foreach ($obj as $value){
   
	$codeOr = $value['code'];
	$codeYandexOr = $value['yandex_code'];
	$query = "SELECT * FROM `rx_station_info` WHERE `code` = '".$codeOr."'";
	$result = mysqli_query($link, $query);
    
	if($result){
    	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    		$id = $row['id'];
    		$yandex_code = $row['yandex_code'];
    		if($yandex_code == ""){
    			$qe = "UPDATE `rx_station_info` SET `yandex_code`='".$codeYandexOr."' WHERE `id`= '".$id."'";
    			echo "Update  ".$id;
            	mysqli_query($link, $qe);
    		}
    
    	}
	}
}

}






?>