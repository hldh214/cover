<?php

namespace Cover;

class Cover
{
    const API = [
        'detail'   => 'http://music.163.com/api/song/detail/?ids=[%s]',
        'playlist' => 'http://music.163.com/api/playlist/detail?id=%s',
        'avatar'   => 'http://music.163.com/api/user/playlist/?offset=0&limit=1&uid=%s',
        'album'    => 'http://music.163.com/api/album/%s'
    ];

    const PATTERN = [
        'detail'   => '#song(\?id=|/)(?<id>\d+)#',
        'playlist' => '#playlist(\?id=|/)(?<id>\d+)#',
        'avatar'   => '#home\?id=(?<id>\d+)#',
        'album'    => '#album(\?id=|/)(?<id>\d+)#'
    ];

    public static function get($url)
    {
        $context = stream_context_create(['http' => ['header' => "Referer: http://music.163.com\r\n" . "\r\n"]]);

        if (preg_match(self::PATTERN['detail'], $url, $song_match)) {
            $res = json_decode(file_get_contents(sprintf(self::API['detail'], $song_match['id']), false, $context), true);
            if (empty($res['songs'])) {
                $cover = false;
            } else {
                $cover = $res['songs'][0]['album']['picUrl'];
            }
        } elseif (preg_match(self::PATTERN['playlist'], $url, $playlist_match)) {
            $res = json_decode(file_get_contents(sprintf(self::API['playlist'], $playlist_match['id']), false, $context), true);
            if (empty($res['result'])) {
                $cover = false;
            } else {
                $cover = $res['result']['coverImgUrl'];
            }
        } elseif (preg_match(self::PATTERN['album'], $url, $album_match)) {
            $res = json_decode(file_get_contents(sprintf(self::API['album'], $album_match['id']), false, $context), true);
            if (empty($res['album'])) {
                $cover = false;
            } else {
                $cover = $res['album']['picUrl'];
            }
        } elseif (preg_match(self::PATTERN['avatar'], $url, $avatar_match)) {
            $res = json_decode(file_get_contents(sprintf(self::API['avatar'], $avatar_match['id']), false, $context), true);
            if (empty($res['playlist'])) {
                $cover = false;
            } else {
                $cover = $res['playlist'][0]['creator']['avatarUrl'];
            }
        } else {
            $cover = false;
        }

        return $cover;
    }
}
