import pika
import xml.etree.ElementTree as ET
import mysql.connector

def create_user(user_data):
    try:
        sql = "INSERT INTO users (id, first_name, last_name, email, telephone, birthday, country, state, city, zip, street, house_number, company_email, company_id, user_role, invoice, calendar_link) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
        user_values = (
            user_data['id'],
            user_data['first_name'],
            user_data['last_name'],
            user_data['email'],
            user_data['telephone'],
            user_data['birthday'],
            user_data['country'],
            user_data['state'],
            user_data['city'],
            user_data['zip'],
            user_data['street'],
            user_data['house_number'],
            user_data['company_email'],
            user_data['company_id'],
            user_data['user_role'],
            user_data['invoice'],
            user_data['calendar_link']
        )
        mysql_cursor.execute(sql, user_values)
        mysql_connection.commit()
        print("User inserted successfully!")
    except mysql.connector.Error as error:
        mysql_connection.rollback()
        print("Failed to insert user:", error)

def update_user(user_data):
    try:
        sql = "UPDATE users SET first_name = %s, last_name = %s, email = %s, telephone = %s, birthday = %s, country = %s, state = %s, city = %s, zip = %s, street = %s, house_number = %s, company_email = %s, company_id = %s, user_role = %s, invoice = %s, calendar_link = %s WHERE id = %s"
        user_values = (
            user_data['first_name'],
            user_data['last_name'],
            user_data['email'],
            user_data['telephone'],
            user_data['birthday'],
            user_data['country'],
            user_data['state'],
            user_data['city'],
            user_data['zip'],
            user_data['street'],
            user_data['house_number'],
            user_data['company_email'],
            user_data['company_id'],
            user_data['user_role'],
            user_data['invoice'],
            user_data['calendar_link'],
            user_data['id']
        )
        mysql_cursor.execute(sql, user_values)
        mysql_connection.commit()
        print("User updated successfully!")
    except mysql.connector.Error as error:
        mysql_connection.rollback()
        print("Failed to update user:", error)

def delete_user(user_id):
    try:
        sql = "DELETE FROM users WHERE id = %s"
        mysql_cursor.execute(sql, (user_id,))
        mysql_connection.commit()
        print("User deleted successfully!")
    except mysql.connector.Error as error:
        mysql_connection.rollback()
        print("Failed to delete user:", error)

def callback(ch, method, properties, body):
    try:
        print("Received message:")
        xml_string = body.decode('utf-8')
        print(xml_string)

        # Parse XML message
        root = ET.fromstring(xml_string)

        # Extract user data
        print("Extracting user data...")
        user_data = {
            'id': root.find('id').text,
            'first_name': root.find('first_name').text,
            'last_name': root.find('last_name').text,
            'email': root.find('email').text,
            'telephone': root.find('telephone').text,
            'birthday': root.find('birthday').text,
            'country': root.find('address/country').text,
            'state': root.find('address/state').text,
            'city': root.find('address/city').text,
            'zip': root.find('address/zip').text,
            'street': root.find('address/street').text,
            'house_number': root.find('address/house_number').text,
            'company_email': root.find('company_email').text,
            'company_id': root.find('company_id').text,
            'user_role': root.find('user_role').text,
            'invoice': root.find('invoice').text,
            'calendar_link': root.find('calendar_link').text
        }
        print("User data extracted successfully.")

        # Print out user data for debugging
        print("User Data:", user_data)

        # Perform CRUD operation
        crud_operation = root.find('crud_operation').text
        if crud_operation == 'create':
            print("Performing create operation...")
            create_user(user_data)
        elif crud_operation == 'update':
            print("Performing update operation...")
            update_user(user_data)
        elif crud_operation == 'delete':
            print("Performing delete operation...")
            delete_user(user_data['id'])

        ch.basic_ack(delivery_tag=method.delivery_tag)
    except Exception as e:
        print("Error processing message:", e)
        ch.basic_nack(delivery_tag=method.delivery_tag, requeue=True)

mysql_connection = mysql.connector.connect(
    host='10.2.160.51',
    port='3307',
    database='frontend',
    user='root',
    password='mypassword'
)
mysql_cursor = mysql_connection.cursor()

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
