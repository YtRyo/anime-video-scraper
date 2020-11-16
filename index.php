<?php

// AuthorBy: RyoDev

use Config\User;
use Client\Http;

foreach (['Config/User.php', 'Client/Http.php'] as $class) {
    require $class;
}

function main()
{
    $http = new Http;
    $config = new User;

    main___:
    system('clear');
    echo '>> Cari anime : ';
    $q = trim(fgets(STDIN));
    if (empty($q)) {
        goto main___;
    } else if ($q === '!setToken') {
        echo '>> Token : ';
        $set_token___ = trim(fgets(STDIN));
        $config->setToken($set_token___);
        goto main___;
    }

    $response___ = json_decode($http->get('https://afara.my.id/api/anime-video-scraper/search', [
        'q' => $q
    ], [
        'Accept: application/json',
        'Content-Type: application/json',
        'Authorization: Bearer ' . $config->token
    ]), true);

    if (isset($response___)) {
        if (!isset($response___['error'])) {
            if (isset($response___['message'])) {
                echo ($response___['message'] === 'Unauthenticated.' ? '[!] Invalid API Token, get a free API Token at afara.my.id' : $response___['message']);
                exit;
            }

            $loop___ = 0;
            foreach ($response___ as $anime___) {
                echo $loop___ . '. ' . $anime___['title'] . "\n";
                $loop___++;
            }

            echo "\n Chose number : ";
            $chose___ = trim(fgets(STDIN));

            if (isset($response___[$chose___])) {
                $animeinfo___ = json_decode($http->get('https://afara.my.id/api/anime-video-scraper/show', [
                    'url' => $response___[$chose___]['link']
                ], [
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $config->token
                ]), true);

                echo "[+] Poducers: {$animeinfo___['info']['producers']} \n";
                echo "[+] Genres: {$animeinfo___['info']['genres']} \n";
                echo "\n======[ Episode ]======\n\n";

                $loop___ = 0;
                foreach ($animeinfo___['episodes'] as $episode___) {
                    echo "{$loop___}. {$episode___['title']} \n";
                    $loop___++;
                }

                echo "\n Chose number : ";
                $chose___ = trim(fgets(STDIN));

                if (isset($animeinfo___['episodes'][$chose___]['link'])) {
                    $videourl___ = json_decode($http->get('https://afara.my.id/api/anime-video-scraper/getvideo', [
                        'url' => $animeinfo___['episodes'][$chose___]['link'],
                    ], [
                        'Accept: application/json',
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $config->token
                    ]), true);

                    if (!isset($videourl___['error'])) {
                        echo 'Link video: ' . $videourl___['url'] . "\n";
                        system('xdg-open ' . $videourl___['url']);
                    } else {
                        echo '[!] Tidak dapat menemukan link video, ini biasa terjadi karna provider nya hanya menyadiakan link download, jadi kunjungi link berikut untuk dapat mendownload video nya secara manual.' . "\n";
                        echo '> Link: ' .  $animeinfo___['episodes'][$chose___]['link'];
                        system('xdg-open ' .  $animeinfo___['episodes'][$chose___]['link']);
                    }
                    exit;
                }
            }
        } else {
            echo ($response___['error'] ?? $response___['message']);
            exit;
        }
    } else {
        echo 'Cek koneksi internet kamu.';
        exit;
    }
}

main();
