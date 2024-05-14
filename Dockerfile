FROM python:3

WORKDIR /TestServer/frontend/Frontend_Laravel
COPY . .

RUN pip install --no-cache-dir -r requirements.txt

CMD [ "python", "heartbeat.py" ]
