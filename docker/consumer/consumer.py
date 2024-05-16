import mysql.connector # type: ignore
import pika # type: ignore
import xml.etree.ElementTree as ET

# Verbinding maken met MySQL-database
mysql_connection = mysql.connector.connect(
    host='10.2.160.51',
    port='3307',
    database='frontend',
    user='root',
    password='mypassword'
)
mysql_cursor = mysql_connection.cursor()

# Functies om gebruikersgegevens in te voegen, bij te werken en te verwijderen in de MySQL-database
def create_user(user_data):
    try:
        sql = "INSERT INTO users (id, first_name, last_name, email, telephone, birthday, country, state, city, zip, street, house_number, company_email, company_id, source, user_role, invoice, calendar_link) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
        user_values = (
            user_data.get('id', ''),
            user_data.get('first_name', ''),
            user_data.get('last_name', ''),
            user_data.get('email', ''),
            user_data.get('telephone', ''),
            user_data.get('birthday', ''),
            user_data.get('country', ''),
            user_data.get('state', ''),
            user_data.get('city', ''),
            user_data.get('zip', ''),
            user_data.get('street', ''),
            user_data.get('house_number', ''),
            user_data.get('company_email', ''),
            user_data.get('company_id', ''),
            user_data.get('source', ''),
            user_data.get('user_role', ''),
            user_data.get('invoice', ''),
            user_data.get('calendar_link', '')
        )
        mysql_cursor.execute(sql, user_values)
        mysql_connection.commit()
        print("User inserted successfully!")
    except mysql.connector.Error as error:
        mysql_connection.rollback()
        print("Failed to insert user:", error)

def update_user(user_data):
    try:
        sql = "UPDATE users SET first_name = %s, last_name = %s, email = %s, telephone = %s, birthday = %s, country = %s, state = %s, city = %s, zip = %s, street = %s, house_number = %s, company_email = %s, company_id = %s, source = %s, user_role = %s, invoice = %s, calendar_link = %s WHERE id = %s"
        user_values = (
            user_data.get('first_name', ''),
            user_data.get('last_name', ''),
            user_data.get('email', ''),
            user_data.get('telephone', ''),
            user_data.get('birthday', ''),
            user_data.get('country', ''),
            user_data.get('state', ''),
            user_data.get('city', ''),
            user_data.get('zip', ''),
            user_data.get('street', ''),
            user_data.get('house_number', ''),
            user_data.get('company_email', ''),
            user_data.get('company_id', ''),
            user_data.get('source', ''),
            user_data.get('user_role', ''),
            user_data.get('invoice', ''),
            user_data.get('calendar_link', ''),
            user_data.get('id', '')
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

# Functie om berichten van de RabbitMQ-queue te verwerken
def callback(ch, method, properties, body):
    try:
        xml_string = body.decode('utf-8')
        root = ET.fromstring(xml_string)

        crud_operation = root.find('crud_operation').text
        if crud_operation not in ['create', 'update', 'delete']:
            raise ValueError("Invalid crud_operation value")
        
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
            'source': root.find('source').text,
            'user_role': root.find('user_role').text,
            'invoice': root.find('invoice').text,
            'calendar_link': root.find('calendar_link').text
        }

        if crud_operation == 'create':
            create_user(user_data)
        elif crud_operation == 'update':
            update_user(user_data)
        elif crud_operation == 'delete':
            user_id = user_data.get('id')
            delete_user(user_id)

        ch.basic_ack(delivery_tag=method.delivery_tag)
    except Exception as e:
        print("Error processing message:", e)
        ch.basic_nack(delivery_tag=method.delivery_tag, requeue=True)

# Verbinding maken met RabbitMQ en wachten op berichten
rabbitmq_connection = pika.BlockingConnection(pika.ConnectionParameters('10.2.160.51', 5672, 'user', 'password'))
channel = rabbitmq_connection.channel()

exchange_name = "amq.topic"
channel.exchange_declare(exchange=exchange_name, exchange_type="topic", durable=True)

queue_name = 'frontend'
channel.queue_declare(queue=queue_name, durable=True)
channel.queue_bind(exchange=exchange_name, queue=queue_name, routing_key="user.crm")

channel.basic_consume(queue=queue_name, on_message_callback=callback)

print(' [*] Waiting for messages. To exit, press CTRL+C')
channel.start_consuming()
