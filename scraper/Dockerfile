FROM python:3.7

WORKDIR /usr/src/app

COPY ./scraper/requirements.txt ./

RUN pip install --upgrade pip && \
    pip install --no-cache-dir -r requirements.txt

COPY ./scraper/scraper.py .

CMD [ "python", "./scraper.py" ]
