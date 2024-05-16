# Use an existing Python image as the base image
FROM python:3.8-slim

# Set the working directory inside the container
WORKDIR /app

# Copy the Python script and requirements file into the container
COPY requirements.txt .
COPY consumer.py .

# Install any necessary dependencies
RUN pip install pika requests
# Command to run the Python script
CMD ["python","-u", "consumer.py"]
