import pika
import xml.etree.ElementTree as ET
import mysql.connector
from datetime import datetime
import hashlib

def create_user(user_data):
    try:
        default_password = "azerty123"
        hashed_password = hashlib.sha256(default_password.encode()).hexdigest()

        sql = """INSERT INTO users (id, first_name, last_name, email, telephone, birthday, country, state, city, zip, street, house_number, 
                 company_email, company_id, user_role, invoice, calendar_link, password, created_at, updated_at) 
                 VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"""
        
        now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        
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
            user_data.get('company_email', None),
            user_data.get('company_id', None),
            user_data['user_role'],
            user_data['invoice'],
            user_data['calendar_link'],
            hashed_password,
            now,
            now
        )
        
        mysql_cursor.execute(sql, user_values)
        mysql_connection.commit()
        print("User inserted successfully!")
    except mysql.connector.Error as error:
        mysql_connection.rollback()
        print("Failed to insert user:", error)

def update_user(user_data):
    try:
        sql = """UPDATE users SET first_name = %s, last_name = %s, email = %s, telephone = %s, birthday = %s, country = %s, state = %s, 
                 city = %s, zip = %s, street = %s, house_number = %s, company_email = %s, company_id = %s, user_role = %s, invoice = %s, 
                 calendar_link = %s, updated_at = %s WHERE id = %s"""
        
        now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        
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
            user_data.get('company_email', None),
            user_data.get('company_id', None),
            user_data['user_role'],
            user_data['invoice'],
            user_data['calendar_link'],
            now,
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

def create_company(company_data):
    try:
        
        default_password = "qwerty123"
        hashed_password = hashlib.sha256(default_password.encode()).hexdigest()
        
        sql = """INSERT INTO companies (id, name, email, telephone, logo, country, state, city, zip, street, house_number, type, invoice, user_role, password, created_at, updated_at) 
                 VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"""
        
        now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        
        company_values = (
            company_data['id'],
            company_data['name'],
            company_data['email'],
            company_data['telephone'],
            company_data['logo'],
            company_data['country'],
            company_data['state'],
            company_data['city'],
            company_data['zip'],
            company_data['street'],
            company_data['house_number'],
            company_data['type'],
            company_data['invoice'],
            'company',
            hashed_password,
            now,
            now
        )
        
        mysql_cursor.execute(sql, company_values)
        mysql_connection.commit()
        print("Company inserted successfully!")
    except mysql.connector.Error as error:
        mysql_connection.rollback()
        print("Failed to insert company:", error)

def update_company(company_data):
    try:
        sql = """UPDATE companies SET name = %s, email = %s, telephone = %s, logo = %s, country = %s, state = %s, city = %s, zip = %s, 
                 street = %s, house_number = %s, type = %s, invoice = %s, user_role = %s, updated_at = %s WHERE id = %s"""
        
        now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        
        company_values = (
            company_data['name'],
            company_data['email'],
            company_data['telephone'],
            company_data['logo'],
            company_data['country'],
            company_data['state'],
            company_data['city'],
            company_data['zip'],
            company_data['street'],
            company_data['house_number'],
            company_data['type'],
            company_data['invoice'],
            'company',
            now,
            company_data['id']
        )
        
        mysql_cursor.execute(sql, company_values)
        mysql_connection.commit()
        print("Company updated successfully!")
    except mysql.connector.Error as error:
        mysql_connection.rollback()
        print("Failed to update company:", error)

def delete_company(company_id):
    try:
        sql = "DELETE FROM companies WHERE id = %s"
        mysql_cursor.execute(sql, (company_id,))
        mysql_connection.commit()
        print("Company deleted successfully!")
    except mysql.connector.Error as error:
        mysql_connection.rollback()
        print("Failed to delete company:", error)

def callback(ch, method, properties, body):
    try:
        print("Received message:")
        xml_string = body.decode('utf-8')
        print(xml_string)

        # Parse XML message
        root = ET.fromstring(xml_string)

        if root.tag == "user":
            process_user(root)
        elif root.tag == "company":
            process_company(root)
        else:
            print("Unknown XML format:", xml_string)

        ch.basic_ack(delivery_tag=method.delivery_tag)
    except Exception as e:
        print("Error processing message:", e)
        ch.basic_nack(delivery_tag=method.delivery_tag, requeue=True)


def process_user(root):
    try:
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
            'company_email': root.find('company_email').text if root.find('company_email') is not None else None,
            'company_id': root.find('company_id').text if root.find('company_id') is not None else None,
            'user_role': root.find('user_role').text,
            'invoice': root.find('invoice').text,
            'calendar_link': root.find('calendar_link').text
        }

        print("Extracting user data...")
        print(f"User Data: {user_data}")

        # Perform CRUD operation
        crud_operation = root.find('crud_operation').text
        print(f"Performing {crud_operation} operation...")
        if crud_operation == 'create':
            create_user(user_data)
        elif crud_operation == 'update':
            update_user(user_data)
        elif crud_operation == 'delete':
            delete_user(user_data['id'])

    except Exception as e:
        print("Error processing user data:", e)

# Process company data
def process_company(root):
    try:
        # Extract company data
        company_data = {
            'id': root.find('id').text,
            'name': root.find('name').text,
            'email': root.find('email').text,
            'telephone': root.find('telephone').text,
            'logo': root.find('logo').text,
            'country': root.find('address/country').text,
            'state': root.find('address/state').text,
            'city': root.find('address/city').text,
            'zip': root.find('address/zip').text,
            'street': root.find('address/street').text,
            'house_number': root.find('address/house_number').text,
            'type': root.find('type').text,
            'invoice': root.find('invoice').text,
        }

        print("Extracting company data...")
        print(f"Company Data: {company_data}")

        # Perform CRUD operation
        crud_operation = root.find('crud_operation').text
        print(f"Performing {crud_operation} operation...")
        if crud_operation == 'create':
            create_company(company_data)
        elif crud_operation == 'update':
            update_company(company_data)
        elif crud_operation == 'delete':
            delete_company(company_data['id'])

    except Exception as e:
        print("Error processing company data:", e)

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
channel.queue_bind(exchange=exchange_name, queue=queue_name, routing_key="company.crm")

# Set up the consumer
channel.basic_consume(queue=queue_name, on_message_callback=callback, auto_ack=False)

print(' [*] Waiting for messages. To exit, press CTRL+C')
channel.start_consuming()
