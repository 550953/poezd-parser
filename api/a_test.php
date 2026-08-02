<?php
header('Content-type: application/json');
$token = $_GET['token'];
//$token = "eyJraWQiOiJmaDZCczhDIiwiYWxnIjoiUlMyNTYifQ.eyJpc3MiOiJodHRwczovL2FwcGxlaWQuYXBwbGUuY29tIiwiYXVkIjoiYmFrc3lzLmNvbS50cmFpbi5wcm92b2RuaWsuYXBwLlRyYWluQXBwIiwiZXhwIjoxNjcwNjY2NTQ3LCJpYXQiOjE2NzA1ODAxNDcsInN1YiI6IjAwMDQzMS41M2RlMTVlMWM4ZWY0YzJjYjI0MjAzNDA2OWNmZDA2NS4wNjQ2IiwiY19oYXNoIjoiVFBYVG9DR3YtZHo5OU40TlJsdWFGdyIsImVtYWlsIjoiYmFrc3lzQHlhLnJ1IiwiZW1haWxfdmVyaWZpZWQiOiJ0cnVlIiwiYXV0aF90aW1lIjoxNjcwNTgwMTQ3LCJub25jZV9zdXBwb3J0ZWQiOnRydWV9.OhneenSplpkNAD3VXvwObHkH9eZBhkcxNs1fJP6YkWS19IZAuY9FIpSi6gIVeF60lqAOIKqtQuTLSLrfvPnU1OHK7FCcwaSpXUjCD2Fd3dSI-u-otWfY0R93GSNuhsCOWH7pRQToh45uxsW0YcfGKEHUh5wgxRI_uB9hVHL0o0iA3NXfrqyeygTEmOt8IH0Cr8x_KDebNJS-uodg-9KpwxxrdwhgvX9_C1Ol7BoAzPCCKBfcAwsSFxxwTQ-6CHYdGrR_A0_pczufeV6hN9H7BJAYS7uSlZvAq1g52Ic7jqErgJi2nk1bUiW-u_J7a5qEisacYB2DIFhJAIAN6z9IdQ";
//print_r(json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1])))));
$array = base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1])));
echo $array;

?>