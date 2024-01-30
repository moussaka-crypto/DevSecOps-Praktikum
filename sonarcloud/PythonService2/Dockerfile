FROM python:3.7.2-alpine3.8

RUN adduser -D team22

WORKDIR /app

COPY requirements.txt /app/

RUN \
    apk add --no-cache gcc musl-dev libffi libffi-dev && \
    pip install -r requirements.txt

COPY .dockerignore /app/.dockerignore

COPY . /app

RUN chown -R team22:team22 /app

USER team22

ENTRYPOINT ["python", "app.py"]
