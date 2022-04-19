FROM owncloudops/nginx:latest

LABEL maintainer="ownCloud GmbH <devops@owncloud.com>" \
    org.label-schema.name="ownCloud Server Updater Server" \
    org.label-schema.vendor="ownCloud GmbH" \
    org.label-schema.schema-version="1.0"

ADD overlay/ /

ADD . /var/www/app/

RUN apk --update add composer && \
    apk --update add php7 php7-fpm php7-xmlwriter && \
    rm -rf /var/www/localhost && \
    rm -f /etc/php7/php-fpm.d/www.conf && \
    composer install --working-dir=/var/www/app --no-dev --optimize-autoloader && \
    composer -q clearcache && \
    rm -rf /var/cache/apk/* && \
    rm -rf /tmp/* && \
    rm -rf /root/.composer/ && \
    mkdir -p /var/run/php && \
    chown -R nginx /var/run/php && \
    mkdir -p /var/lib/php/tmp_upload && \
    mkdir -p /var/lib/php/soap_cache && \
    mkdir -p /var/lib/php/session && \
    chown -R nginx /var/lib/php && \
    chown nginx /etc/php7/php.ini && \
    chown -R nginx:nginx /var/www/app

EXPOSE 8080

USER nginx

STOPSIGNAL SIGTERM

ENTRYPOINT ["/usr/bin/entrypoint"]
HEALTHCHECK --interval=30s --timeout=5s --retries=3 CMD /usr/bin/healthcheck
WORKDIR /var/www/app
CMD []
