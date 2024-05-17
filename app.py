# from flask import Flask, request, render_template
# import mysql.connector
# from datetime import datetime
# import os

# app = Flask(__name__)
# app.debug = True

# app.logger.info('Info level log')
# app.logger.warning('Warning level log')

# @app.route('/')
# def index():
#     template_dir = os.path.join(app.root_path, 'templates')
#     return render_template('signup.html')

# @app.route('/submit-form', methods=['POST'])
# def submit_form():
#     name = request.form['name']
#     email = request.form['email']
#     number = request.form['phone']
#     username = request.form['userid']
#     password = request.form['password']
#     confirm_password = request.form['confirm_password']

#     # Check if passwords match
#     if password != confirm_password:
#         return "Passwords do not match!"

#     # Connect to the database
#     connection = mysql.connector.connect(host="localhost", user="root", password="", database="login")
#     cursor = connection.cursor()

#     # Insert form data into the database
#     sql = "INSERT INTO users (name, email, number, username, password, created_at) VALUES (%s, %s, %s, %s, %s, %s)"
#     values = (name, email, number, username, password, datetime.now())
#     cursor.execute(sql, values)
#     connection.commit()

#     cursor.close()
#     connection.close()

#     return "Form submitted successfully!"

# if __name__ == '__main__':
#     app.run(debug=True)


from flask import Flask, request, render_template
import mysql.connector
from datetime import datetime
import os

app = Flask(__name__)
app.debug = True
app.logger.info('Info level log')
app.logger.warning('Warning level log')

@app.route('/')
def index():
    template_dir = os.path.join(app.root_path, 'templates')
    return render_template('signup.html')

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
    from gunicorn.app.base import Base

    class FlaskApplication(Base):
        def __init__(self, app, options=None):
            self.options = options or {}
            self.application = app
            super().__init__(self.application)

        def load_config(self):
            config = {
                'bind': '0.0.0.0:5000',  # Change the port number if desired
                'workers': 4,  # Adjust the number of worker processes
                'accesslog': '-',  # Log to stdout/stderr
                'errorlog': '-',  # Log to stdout/stderr
            }
            for key, value in self.options.items():
                config.setdefault(key, value)
            return config

    options = {
        'bind': '%s:%s' % ('0.0.0.0', '5000'),
        'workers': 4,
    }
    FlaskApplication(app, options).run()
