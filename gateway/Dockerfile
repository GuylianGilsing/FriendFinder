FROM devopsfaith/krakend:2.5

COPY ./krakend.json /etc/krakend/krakend.json

# Convert http -> https (final deployment will use https)
RUN  sed -i "s/http:\/\//https:\/\//g" /etc/krakend/krakend.json

EXPOSE 8080
