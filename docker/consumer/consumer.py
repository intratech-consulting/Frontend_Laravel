import pika
import xml.etree.ElementTree as ET

def callback(ch, method, properties, body):
    try:
        print("Received message:")
        xml_string = body.decode('utf-8')
        print(xml_string)

        # Parse XML message
        root = ET.fromstring(xml_string)

        # Extract required fields
        routing_key = root.find('routing_key').text
        crud_operation = root.find('crud_operation').text
        user_id = root.find('id').text
        first_name = root.find('first_name').text
        last_name = root.find('last_name').text
        email = root.find('email').text
        telephone = root.find('telephone').text
        birthday = root.find('birthday').text
        country = root.find('address/country').text
        state = root.find('address/state').text
        city = root.find('address/city').text
        zip_code = root.find('address/zip').text
        street = root.find('address/street').text
        house_number = root.find('address/house_number').text
        company_email = root.find('company_email').text
        company_id = root.find('company_id').text
        source = root.find('source').text
        user_role = root.find('user_role').text
        invoice = root.find('invoice').text
        calendar_link = root.find('calendar_link').text

        print("Routing Key:", routing_key)
        print("CRUD Operation:", crud_operation)
        print("User ID:", user_id)
        print("First Name:", first_name)
        print("Last Name:", last_name)
        print("Email:", email)
        print("Telephone:", telephone)
        print("Birthday:", birthday)
        print("Country:", country)
        print("State:", state)
        print("City:", city)
        print("Zip Code:", zip_code)
        print("Street:", street)
        print("House Number:", house_number)
        print("Company Email:", company_email)
        print("Company ID:", company_id)
        print("Source:", source)
        print("User Role:", user_role)
        print("Invoice:", invoice)
        print("Calendar Link:", calendar_link)

        ch.basic_ack(delivery_tag=method.delivery_tag)
    except Exception as e:
        print("Error processing message:", e)
        ch.basic_nack(delivery_tag=method.delivery_tag, requeue=True)

# Establish connection to RabbitMQ server
credentials = pika.PlainCredentials('user', 'password')  
rabbitmq_connection = pika.BlockingConnection(pika.ConnectionParameters('10.2.160.51', 5672, '/', credentials))

channel = rabbitmq_connection.channel()

# Declare the exchange
exchange_name = "amq.topic"
channel.exchange_declare(exchange=exchange_name, exchange_type="topic", durable=True)

# Declare and bind a queue
queue_name = "frontend"
channel.queue_declare(queue=queue_name, durable=True)
channel.queue_bind(exchange=exchange_name, queue=queue_name, routing_key="user.crm")

# Set up the consumer
channel.basic_consume(queue=queue_name, on_message_callback=callback, auto_ack=True)

print(' [*] Waiting for messages. To exit, press CTRL+C')
channel.start_consuming()
