import json
import colorlog
import requests
import json
import uuid
import pika
import xml.etree.ElementTree as ET
import mysql.connector
from datetime import datetime
import bcrypt
import logging

def configure_logger(logger):
    # Set level for the logger
    logger.setLevel(logging.DEBUG)

    # Create a color formatter
    formatter = colorlog.ColoredFormatter(
        '%(log_color)s%(asctime)s:%(levelname)s:%(name)s:%(message)s',
        datefmt='%Y-%m-%d %H:%M:%S',
        log_colors={
            'DEBUG': 'cyan',
            'INFO': 'green',
            'WARNING': 'yellow',
            'ERROR': 'red',
            'CRITICAL': 'red,bg_white',
        },
    )

    # Create a stream handler and set the formatter
    handler = logging.StreamHandler()
    handler.setFormatter(formatter)

    # Add the handler to the logger
    logger.addHandler(handler)


def init_logger(name):
    logger = logging.getLogger(name)
    configure_logger(logger)
    return logger

log = init_logger(__name__)

GENERAL_IP=

log.info(f"Starting consumer... with GENERAL_IP: {GENERAL_IP}")

def create_user(user_data):
    log.debug(f"Creating user: {user_data}")
    try:
        default_password = "azerty123"
        hashed_password = bcrypt.hashpw(default_password.encode('utf-8'), bcrypt.gensalt()).decode('utf-8')

        hashed_password = hashed_password.replace('$2b$', '$2y$', 1)

        sql = """INSERT INTO users (id, first_name, last_name, email, telephone, birthday, country, state, city, zip, street, house_number,
                 company_email, company_id, user_role, invoice, calendar_link, password, created_at, updated_at)
                 VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"""

        now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

        persoonlijkId = get_next_persoonlijk_id()
        log.debug(f"Next persoonlijkId: {persoonlijkId}")

        user_values = (
            persoonlijkId,
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
        log.debug(f"User data: {user_values}")

        mysql_cursor.execute(sql, user_values)
        mysql_connection.commit()
        log.info("User inserted successfully!")


        #MasterUuid
        masterUuid_url = f"http://{GENERAL_IP}:6000/addServiceId"
        masterUuid_payload = json.dumps(
            {
                "MasterUuid": f"{user_data['id']}",
                "Service": "frontend",
                "ServiceId": f"{persoonlijkId}"
            }
        )
        uid_headers={
        'Content-type':'application/json',
        'Accept':'application/json'
        }
        log.debug(f"uid: {user_data['id']}")
        response = requests.request("POST", masterUuid_url, headers=uid_headers ,data=masterUuid_payload)
        log.debug(response)

    except mysql.connector.Error as error:
        mysql_connection.rollback()
        log.error("Failed to insert user:", error)

def update_user(user_data):
    try:
        sql = "UPDATE users SET "
        values = []

        #get user id from masteruid
        masterUuid_url = f"http://{GENERAL_IP}:6000/getServiceId"
        masterUuid_payload = json.dumps(
            {
                "MASTERUUID": f"{user_data['id']}",
                "Service": "frontend",
            }
        )
        uid_headers={
        'Content-type':'application/json',
        'Accept':'application/json'
        }
        print(f"uid: {user_data['id']}")
        response = requests.request("POST", masterUuid_url, headers=uid_headers ,data=masterUuid_payload)
        data = response.json()
        print(data)
        user_pk=data["frontend"]
        print(user_pk)

        userID = user_pk

        if user_data.get('first_name'):
            sql += "first_name = %s, "
            values.append(user_data['first_name'])
        if user_data.get('last_name'):
            sql += "last_name = %s, "
            values.append(user_data['last_name'])
        if user_data.get('email'):
            sql += "email = %s, "
            values.append(user_data['email'])
        if user_data.get('telephone'):
            sql += "telephone = %s, "
            values.append(user_data['telephone'])
        if user_data.get('birthday'):
            sql += "birthday = %s, "
            values.append(user_data['birthday'])
        if user_data.get('country'):
            sql += "country = %s, "
            values.append(user_data['country'])
        if user_data.get('state'):
            sql += "state = %s, "
            values.append(user_data['state'])
        if user_data.get('city'):
            sql += "city = %s, "
            values.append(user_data['city'])
        if user_data.get('zip'):
            sql += "zip = %s, "
            values.append(user_data['zip'])
        if user_data.get('street'):
            sql += "street = %s, "
            values.append(user_data['street'])
        if user_data.get('house_number'):
            sql += "house_number = %s, "
            values.append(user_data['house_number'])
        if user_data.get('company_email'):
            sql += "company_email = %s, "
            values.append(user_data['company_email'])
        if user_data.get('company_id'):
            sql += "company_id = %s, "
            values.append(user_data['company_id'])
        if user_data.get('user_role'):
            sql += "user_role = %s, "
            values.append(user_data['user_role'])
        if user_data.get('invoice'):
            sql += "invoice = %s, "
            values.append(user_data['invoice'])
        if user_data.get('calendar_link'):
            sql += "calendar_link = %s, "
            values.append(user_data['calendar_link'])

        now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        sql += "updated_at = %s WHERE id = %s"
        values.append(now)
        values.append(userID)

        mysql_cursor.execute(sql, values)
        mysql_connection.commit()
        print("User updated successfully!")


    except mysql.connector.Error as error:
        mysql_connection.rollback()
        print("Failed to update user:", error)

def delete_user(user_id):
    try:
        sql = "DELETE FROM users WHERE id = %s"

        print(user_id)

        #get user id from masteruid
        masterUuid_url = f"http://{GENERAL_IP}:6000/getServiceId"
        masterUuid_payload = json.dumps(
            {
                "MASTERUUID": f"{user_id}",
                "Service": "frontend",
            }
        )
        uid_headers={
        'Content-type':'application/json',
        'Accept':'application/json'
        }
        print(f"uid: {user_id}")
        response = requests.request("POST", masterUuid_url, headers=uid_headers ,data=masterUuid_payload)
        print(response)

        data = response.json()
        print(data)
        user_pk=data["frontend"]
        print(user_pk)

        userID = user_pk
        values = []
        values.append(userID)

        mysql_cursor.execute(sql, values)
        mysql_connection.commit()
        print("User deleted successfully!")


        #Update user id
        masterUuid_url = f"http://{GENERAL_IP}:6000/updateServiceId"
        masterUuid_payload = json.dumps(
            {
                "MASTERUUID": f"{user_id}",
                "Service": "frontend",
                "ServiceId": "NULL",

            }
        )
        uid_headers={
        'Content-type':'application/json',
        'Accept':'application/json'
        }
        print(f"uid: {user_id}")
        response2 = requests.request("POST", masterUuid_url, headers=uid_headers ,data=masterUuid_payload)
        print(response2)



    except mysql.connector.Error as error:
        mysql_connection.rollback()
        print("Failed to delete user:", error)

def create_company(company_data):
    try:

        default_password = "qwerty123"
        hashed_password = bcrypt.hashpw(default_password.encode('utf-8'), bcrypt.gensalt()).decode('utf-8')

        hashed_password = hashed_password.replace('$2b$', '$2y$', 1)

        persoonlijkId = get_next_persoonlijk_id_company()


        sql = """INSERT INTO companies (id, name, email, telephone, logo, country, state, city, zip, street, house_number, type, invoice, user_role, password, created_at, updated_at)
                 VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"""

        now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

        company_values = (
            persoonlijkId,
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

        #MasterUuid
        masterUuid_url = f"http://{GENERAL_IP}:6000/addServiceId"
        masterUuid_payload = json.dumps(
            {
                "MasterUuid": f"{company_data['id']}",
                "Service": "frontend",
                "ServiceId": f"{persoonlijkId}"
            }
        )
        uid_headers={
        'Content-type':'application/json',
        'Accept':'application/json'
        }
        print(f"uid: {company_data['id']}")
        response = requests.request("POST", masterUuid_url, headers=uid_headers ,data=masterUuid_payload)
        print(response)
            

    except mysql.connector.Error as error:
        mysql_connection.rollback()
        print("Failed to insert company:", error)

def update_company(company_data):
    try:
        sql = "UPDATE companies SET "
        values = []

    #get company id from masteruid
        masterUuid_url = f"http://{GENERAL_IP}:6000/getServiceId"
        masterUuid_payload = json.dumps(
            {
                "MASTERUUID": f"{company_data['id']}",
                "Service": "frontend",
            }
        )
        uid_headers={
        'Content-type':'application/json',
        'Accept':'application/json'
        }
        print(f"uid: {company_data['id']}")
        response = requests.request("POST", masterUuid_url, headers=uid_headers ,data=masterUuid_payload)
        data = response.json()
        print(data)
        company_pk=data["frontend"]
        print(company_pk)

        companyID = company_pk

        if company_data.get('name'):
            sql += "name = %s, "
            values.append(company_data['name'])
        if company_data.get('email'):
            sql += "email = %s, "
            values.append(company_data['email'])
        if company_data.get('telephone'):
            sql += "telephone = %s, "
            values.append(company_data['telephone'])
        if company_data.get('logo'):
            sql += "logo = %s, "
            values.append(company_data['logo'])
        if company_data.get('country'):
            sql += "country = %s, "
            values.append(company_data['country'])
        if company_data.get('state'):
            sql += "state = %s, "
            values.append(company_data['state'])
        if company_data.get('city'):
            sql += "city = %s, "
            values.append(company_data['city'])
        if company_data.get('zip'):
            sql += "zip = %s, "
            values.append(company_data['zip'])
        if company_data.get('street'):
            sql += "street = %s, "
            values.append(company_data['street'])
        if company_data.get('house_number'):
            sql += "house_number = %s, "
            values.append(company_data['house_number'])
        if company_data.get('type'):
            sql += "type = %s, "
            values.append(company_data['type'])
        if company_data.get('invoice'):
            sql += "invoice = %s, "
            values.append(company_data['invoice'])

        now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        sql += "updated_at = %s WHERE id = %s"
        values.append(now)
        values.append(companyID)
        mysql_cursor.execute(sql, values)
        mysql_connection.commit()
        print("Company updated successfully!")
    except mysql.connector.Error as error:
        mysql_connection.rollback()
        print("Failed to update company:", error)

def delete_company(company_id):
    try:
        sql = "DELETE FROM companies WHERE id = %s"

        #get company id from masteruid
        masterUuid_url = f"http://{GENERAL_IP}:6000/getServiceId"
        masterUuid_payload = json.dumps(
            {
                "MASTERUUID": f"{company_id}",
                "Service": "frontend",
            }
        )
        uid_headers={
        'Content-type':'application/json',
        'Accept':'application/json'
        }
        print(f"uid: {company_id}")
        response = requests.request("POST", masterUuid_url, headers=uid_headers ,data=masterUuid_payload)
        print(response)

        data = response.json()
        print(data)
        company_pk=data["frontend"]
        print(company_pk)

        companyID = company_pk
        values = []
        values.append(companyID)

        mysql_cursor.execute(sql, values)
        mysql_connection.commit()
        print("Company deleted successfully!")
        

        #Update company id
        masterUuid_url = f"http://{GENERAL_IP}:6000/updateServiceId"
        masterUuid_payload = json.dumps(
            {
                "MASTERUUID": f"{company_id}",
                "Service": "frontend",
                "ServiceId": "NULL",

            }
        )
        uid_headers={
        'Content-type':'application/json',
        'Accept':'application/json'
        }
        print(f"uid: {company_id}")
        response2 = requests.request("POST", masterUuid_url, headers=uid_headers ,data=masterUuid_payload)
        print(response2)



    except mysql.connector.Error as error:
        mysql_connection.rollback()
        print("Failed to delete company:", error)

def create_event(event_data):
    try:
        sql = """INSERT INTO events (id, title, date, start_time, end_time, location, speaker_user_id, speaker_company_id, max_registrations, available_seats, description, created_at, updated_at)
                 VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"""

        now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        persoonlijkId = get_next_persoonlijk_id_event()

        speaker_user_id = event_data['speaker']['user_id']
        speaker_user_event_id = get_event_id_from_master(speaker_user_id)

        speaker_company_id = event_data['speaker']['company_id']
        speaker_company_event_id = get_event_id_from_master(speaker_company_id)

        event_values = (
            persoonlijkId,
            event_data['title'],
            event_data['date'],
            event_data['start_time'],
            event_data['end_time'],
            event_data['location'],
            speaker_user_event_id,
            speaker_company_event_id,
            event_data['max_registrations'],
            event_data['available_seats'],
            event_data['description'],
            now,
            now
        )

        mysql_cursor.execute(sql, event_values)
        mysql_connection.commit()
        print("Event inserted successfully!")


        #MasterUuid
        masterUuid_url = f"http://{GENERAL_IP}:6000/addServiceId"
        masterUuid_payload = json.dumps(
            {
                "MasterUuid": f"{event_data['id']}",
                "Service": "frontend",
                "ServiceId": f"{persoonlijkId}"
            }
        )
        uid_headers={
        'Content-type':'application/json',
        'Accept':'application/json'
        }
        print(f"uid: {event_data['id']}")
        response = requests.request("POST", masterUuid_url, headers=uid_headers ,data=masterUuid_payload)
        print(response)
            

    except mysql.connector.Error as error:
        mysql_connection.rollback()
        print("Failed to insert event:", error)

def update_event(event_data):
    try:
        sql = "UPDATE events SET "
        values = []

    #get event id from masteruid
        masterUuid_url = f"http://{GENERAL_IP}:6000/getServiceId"
        masterUuid_payload = json.dumps(
            {
                "MASTERUUID": f"{event_data['id']}",
                "Service": "frontend",
            }
        )
        uid_headers={
        'Content-type':'application/json',
        'Accept':'application/json'
        }
        print(f"uid: {event_data['id']}")
        response = requests.request("POST", masterUuid_url, headers=uid_headers ,data=masterUuid_payload)
        data = response.json()
        print(data)
        event_pk=data["frontend"]
        print(event_pk)

        eventID = event_pk



        speaker_user_id = event_data['speaker']['user_id']
        speaker_user_event_id = get_event_id_from_master(speaker_user_id)

        speaker_company_id = event_data['speaker']['company_id']
        speaker_company_event_id = get_event_id_from_master(speaker_company_id)



        if event_data.get('date'):
            sql += "date = %s, "
            values.append(event_data['date'])
        if event_data.get('start_time'):
            sql += "start_time = %s, "
            values.append(event_data['start_time'])
        if event_data.get('title'):
            sql += "title = %s, "
            values.append(event_data['title'])
        if event_data.get('end_time'):
            sql += "end_time = %s, "
            values.append(event_data['end_time'])
        if event_data.get('location'):
            sql += "location = %s, "
            values.append(event_data['location'])
            
        sql += "speaker_user_id = %s, "
        values.append(speaker_user_event_id)
    
        sql += "speaker_company_id = %s, "
        values.append(speaker_company_event_id)      

        if event_data.get('max_registrations'):
            sql += "max_registrations = %s, "
            values.append(event_data['max_registrations'])
        if event_data.get('available_seats'):
            sql += "available_seats = %s, "
            values.append(event_data['available_seats'])
        if event_data.get('description'):
            sql += "description = %s, "
            values.append(event_data['description'])

        now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        sql += "updated_at = %s WHERE id = %s"
        values.append(now)
        values.append(eventID)

        mysql_cursor.execute(sql, values)
        mysql_connection.commit()
        print("Event updated successfully!")
    except mysql.connector.Error as error:
        mysql_connection.rollback()
        print("Failed to update event:", error)

def delete_event(event_id):
    try:
        sql = "DELETE FROM events WHERE id = %s"
        
        #get event id from masteruid
        masterUuid_url = f"http://{GENERAL_IP}:6000/getServiceId"
        masterUuid_payload = json.dumps(
            {
                "MASTERUUID": f"{event_id}",
                "Service": "frontend",
            }
        )
        uid_headers={
        'Content-type':'application/json',
        'Accept':'application/json'
        }
        print(f"uid: {event_id}")
        response = requests.request("POST", masterUuid_url, headers=uid_headers ,data=masterUuid_payload)
        print(response)

        data = response.json()
        print(data)
        event_pk=data["frontend"]
        print(event_pk)

        eventID = event_pk
        values = []
        values.append(eventID)


        mysql_cursor.execute(sql, values)
        mysql_connection.commit()
        print("Event deleted successfully!")


        #Update event id
        masterUuid_url = f"http://{GENERAL_IP}:6000/updateServiceId"
        masterUuid_payload = json.dumps(
            {
                "MASTERUUID": f"{event_id}",
                "Service": "frontend",
                "ServiceId": "NULL",

            }
        )
        uid_headers={
        'Content-type':'application/json',
        'Accept':'application/json'
        }
        print(f"uid: {event_id}")
        response2 = requests.request("POST", masterUuid_url, headers=uid_headers ,data=masterUuid_payload)
        print(response2)



    except mysql.connector.Error as error:
        mysql_connection.rollback()
        print("Failed to delete event:", error)

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
        elif root.tag == "event":
            process_event(root)
        else:
            print("Unknown XML format:", xml_string)

        ch.basic_ack(delivery_tag=method.delivery_tag)
    except Exception as e:
        print("Error processing message:", e)
        ch.basic_nack(delivery_tag=method.delivery_tag, requeue=False)


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


def process_event(root):
    try:
        # Extract event data
        event_data = {
            'id': root.find('id').text,
            'title': root.find('title').text,
            'date': root.find('date').text,
            'start_time': root.find('start_time').text,
            'end_time': root.find('end_time').text,
            'location': root.find('location').text,
            'speaker': {
                'user_id': root.find('speaker/user_id').text,
                'company_id': root.find('speaker/company_id').text
            },
            'max_registrations': root.find('max_registrations').text,
            'available_seats': root.find('available_seats').text,
            'description': root.find('description').text
        }

        print("Extracting events data...")
        print(f"Event Data: {event_data}")

        # Perform CRUD operation
        crud_operation = root.find('crud_operation').text
        print(f"Performing {crud_operation} operation...")
        if crud_operation == 'create':
            create_event(event_data)
        elif crud_operation == 'update':
            update_event(event_data)
        elif crud_operation == 'delete':
            delete_event(event_data['id'])

    except Exception as e:
        print("Error processing event data:", e)

def get_next_persoonlijk_id():
    try:
        mysql_cursor.execute("SELECT MAX(id) FROM users")
        result = mysql_cursor.fetchone()[0]
        if result is not None:
            return result + 1
        else:
            return 2000  # Start from 2 000 if no users exist yet
    except mysql.connector.Error as error:
        print("Failed to get next persoonlijkId:", error)
        return None


def get_next_persoonlijk_id_company():
    try:
        mysql_cursor.execute("SELECT MAX(id) FROM companies")
        result = mysql_cursor.fetchone()[0]
        if result is not None:
            return result + 1
        else:
            return 200000  # Start from 200 000 if no company exist yet
    except mysql.connector.Error as error:
        print("Failed to get next persoonlijkId:", error)
        return None


def get_next_persoonlijk_id_event():
    try:
        mysql_cursor.execute("SELECT MAX(id) FROM events")
        result = mysql_cursor.fetchone()[0]
        if result is not None:
            return result + 1
        else:
            return 20000  # Start from 20 000 if no event exist yet
    except mysql.connector.Error as error:
        print("Failed to get next persoonlijkId:", error)
        return None

def get_event_id_from_master(id):
    try:
        # Construct the URL and payload
        masterUuid_url = f"http://{GENERAL_IP}:6000/getServiceId"
        masterUuid_payload = {
            "MASTERUUID": id,
            "Service": "frontend",
        }
        uid_headers = {
            'Content-type': 'application/json',
            'Accept': 'application/json'
        }

        # Send the POST request
        response = requests.post(masterUuid_url, headers=uid_headers, json=masterUuid_payload)
        if response.status_code == 200:
            data = response.json()
            event_id = data.get("frontend")
            if event_id:
                return event_id
            else:
                print(f"Event ID not found for ID: {id}")
        else:
            print(f"Failed to retrieve event ID for ID: {id}. Status code: {response.status_code}")

    except Exception as e:
        print(f"Error retrieving event ID for ID: {id}. Error: {e}")

    return None



mysql_connection = mysql.connector.connect(
    host=GENERAL_IP,
    port='3307',
    database='frontend',
    user='root',
    password='mypassword'
)
mysql_cursor = mysql_connection.cursor()

credentials = pika.PlainCredentials('user', 'password')
rabbitmq_connection = pika.BlockingConnection(pika.ConnectionParameters(GENERAL_IP, 5672, '/', credentials))

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
