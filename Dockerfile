FROM mileschou/phalcon:7.0-apache
MAINTAINER Jim <hldh214@gmail.com>

RUN echo "deb http://mirrors.ustc.edu.cn/debian/ jessie main contrib non-free" > /etc/apt/sources.list && \
apt-get update && \
    apt-get install -y git && \
    git clone https://github.com/hldh214/cover.git && \
    chown -R www-data:www-data /var/www/html/cover/runtime/ && \
    sed -i 's/\/var\/www\/html/\/var\/www\/html\/cover\/public/' /etc/apache2/sites-available/000-default.conf && \
    ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/
