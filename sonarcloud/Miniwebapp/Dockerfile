# Example: docker build . -t dsvw && docker run -p 65412:65412 dsvw

FROM alpine:3.11

RUN adduser -D team22

RUN apk --no-cache add git python3 py-lxml \
    && rm -rf /var/cache/apk/*

WORKDIR /home/team22
RUN git clone https://github.com/stamparm/DSVW

RUN chown -R team22:team22 /home/team22

WORKDIR /home/team22/DSVW
RUN sed -i 's/127.0.0.1/0.0.0.0/g' dsvw.py

EXPOSE 65412

USER team22

CMD ["python3", "dsvw.py"]
