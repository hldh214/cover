<?php

use Phalcon\Mvc\Controller;

class CoverController extends Controller
{
    // Streaking
    const song_detail_api = 'http://music.163.com/api/song/detail/?ids=[%s]';
    const playlist_api = 'http://music.163.com/api/playlist/detail?id=%s';
    const avatar_api = 'http://music.163.com/api/user/playlist/?offset=0&limit=0&uid=%s';

    // need Referer
    const album_api = 'http://music.163.com/api/album/%s';
    const program_api = 'http://music.163.com/api/dj/program/detail?id=%s';

    // regex pattern
    const song_detail_pattern = '#song(\?id=|/)(\d+)#';
    const playlist_pattern = '#playlist(\?id=|/)(\d+)#';
    const album_pattern = '#album(\?id=|/)(\d+)#';
    const program_pattern = '#(program|dj)\?id=(\d+)#';
    const avatar_pattern = '#home\?id=(\d+)#';

    public function indexAction()
    {
        if ($this->request->isPost()) {
            $url = $this->request->getPost("url");

            $context = stream_context_create(['http' => ['header' => "Referer: http://music.163.com\r\n" . "\r\n"]]);

            if (preg_match(self::song_detail_pattern, $url, $song_match)) {
                $res = json_decode(file_get_contents(sprintf(self::song_detail_api, $song_match[2]), false, $context), true);
                if (empty($res['songs'])) {
                    $cover = false;
                } else {
                    $cover = $res['songs'][0]['album']['picUrl'];
                }
            } elseif (preg_match(self::playlist_pattern, $url, $playlist_match)) {
                $res = json_decode(file_get_contents(sprintf(self::playlist_api, $playlist_match[2]), false, $context), true);
                if (empty($res['result'])) {
                    $cover = false;
                } else {
                    $cover = $res['result']['coverImgUrl'];
                }
            } elseif (preg_match(self::album_pattern, $url, $album_match)) {
                $res = json_decode(file_get_contents(sprintf(self::album_api, $album_match[2]), false, $context), true);
                if (empty($res['album'])) {
                    $cover = false;
                } else {
                    $cover = $res['album']['picUrl'];
                }
            } elseif (preg_match(self::program_pattern, $url, $program_match)) {
                $res = json_decode(file_get_contents(sprintf(self::program_api, $program_match[2]), false, $context), true);
                if (empty($res['program'])) {
                    $cover = false;
                } else {
                    $cover = $res['program']['mainSong']['album']['picUrl'];
                }
            } elseif (preg_match(self::avatar_pattern, $url, $avatar_match)) {
                $res = json_decode(file_get_contents(sprintf(self::avatar_api, $avatar_match[1]), false, $context), true);
                if (empty($res['playlist'])) {
                    $cover = false;
                } else {
                    $cover = $res['playlist'][0]['creator']['avatarUrl'];
                }
            } else {
                $cover = false;
            }

            echo $cover ? substr($cover, 5) : '';

            $this->view->disable();
        }
    }
}
