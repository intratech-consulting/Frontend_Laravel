# Use an existing Python image as the base image
FROM python:3.9-slim

# Set the working directory inside the container
WORKDIR /app

# Copy the Python script and requirements file into the container
COPY requirements.txt .
COPY consumer.py .

# Install any necessary dependencies
RUN pip install --no-cache-dir -r requirements.txt

# Command to run the Python script
CMD ["python","-u", "consumer.py"]
