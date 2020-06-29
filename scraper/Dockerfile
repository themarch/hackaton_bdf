FROM python:3.7

WORKDIR /usr/src/app

COPY ./scraper/requirements.txt ./
COPY ./scraper/scraper.py ./
RUN pip install -r requirements.txt

CMD [ "python scraper.py" ]
