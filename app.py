from flask import Flask, request, render_template
import mysql.connector
from datetime import datetime

app = Flask(__name__)

@app.route('/')
def index():
    return render_template('sign-in.html')

@app.route('/submit-form', methods=['POST'])
def submit_form():
    name = request.form['name']
    email = request.form['email']
    number = request.form['phone']
    username = request.form['userid']
    password = request.form['password']
    confirm_password = request.form['confirm_password']

    # Check if passwords match
    if password != confirm_password:
        return "Passwords do not match!"

    # Connect to the database
    connection = mysql.connector.connect(host="localhost", user="root", password="", database="login")
    cursor = connection.cursor()

    # Insert form data into the database
    sql = "INSERT INTO users (name, email, number, username, password, created_at) VALUES (%s, %s, %s, %s, %s, %s)"
    values = (name, email, number, username, password, datetime.now())
    cursor.execute(sql, values)
    connection.commit()

    cursor.close()
    connection.close()

    return "Form submitted successfully!"

if __name__ == '__main__':
    app.run(debug=True)
