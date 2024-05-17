import mysql.connector

connection = mysql.connector.connect(host="localhost", user="root", password="", database="login")

if connection.is_connected():
    print("Connected Successfully...")

    # perform database operations
    
else:
    print("Failed to connect.")

connection.close()