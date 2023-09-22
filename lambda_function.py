import httpx
import re


def parse_detail(response):
    return response['songs'][0]['album']['picUrl']


def parse_playlist(response):
    return response['result']['coverImgUrl']


def parse_avatar(response):
    return response['playlist'][0]['creator']['avatarUrl']


def parse_album(response):
    return response['album']['picUrl']


def parse_dj(response):
    return response['program']['coverUrl']


def parse_djradio(response):
    return response['djRadio']['picUrl']


apis = {
    'detail': 'https://music.163.com/api/song/detail/?ids=[{id}]',
    'playlist': 'https://music.163.com/api/playlist/detail?id={id}',
    'avatar': 'https://music.163.com/api/user/playlist/?offset=0&limit=1&uid={id}',
    'album': 'https://music.163.com/api/album/{id}',
    'dj': 'https://music.163.com/api/dj/program/detail?id={id}',
    'program': 'https://music.163.com/api/dj/program/detail?id={id}',
    'djradio': 'https://music.163.com/api/djradio/get?id={id}'
}
patterns = {
    'detail': r'song(\?id=|/)(?P<id>\d+)',
    'playlist': r'playlist(\?id=|/)(?P<id>\d+)',
    'avatar': r'home\?id=(?P<id>\d+)',
    'album': r'album(\?id=|/)(?P<id>\d+)',
    'dj': r'dj(\?id=|/)(?P<id>\d+)',
    'program': r'program(\?id=|/)(?P<id>\d+)',
    'djradio': r'djradio(\?id=|/)(?P<id>\d+)'
}

parsers = {
    'detail': parse_detail,
    'playlist': parse_playlist,
    'avatar': parse_avatar,
    'album': parse_album,
    'dj': parse_dj,
    'program': parse_dj,
    'djradio': parse_djradio
}


def get_cover(url):
    cover = ''

    for method, pattern in patterns.items():
        pattern = patterns[method]
        result = re.search(pattern, url)

        if not result:
            continue

        cover = query_by_id(result.group('id'), method)

    return cover


def query_by_id(item_id, method):
    url = apis[method]
    res = httpx.get(url.format(id=item_id)).json()

    try:
        return parsers[method](res)
    except KeyError:
        return ''


def lambda_handler(event, _context):
    return {
        'statusCode': 200,
        'body': get_cover(event['queryStringParameters']['url'])
    }
