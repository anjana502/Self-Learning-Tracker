# Store this code in 'app.py' file

from flask import Flask, render_template, request, redirect, url_for, session
from flask_mysqldb import MySQL
import MySQLdb.cursors
from datetime import datetime
import subprocess as sp
import re


app = Flask(__name__)


app.secret_key = 'your secret key'

app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = ''
app.config['MYSQL_DB'] = 'selftracker'

mysql = MySQL(app)

@app.route('/')
@app.route('/login', methods =['GET', 'POST'])
def login():
	msg = ''
	if request.method == 'POST' and 'username' in request.form and 'password' in request.form:
		username = request.form['username']
		password = request.form['password']
		cursor = mysql.connection.cursor(MySQLdb.cursors.DictCursor)
		cursor.execute('SELECT * FROM user WHERE username = % s AND password = % s', (username, password, ))
		account = cursor.fetchone()
		if account:
			session['loggedin'] = True
			session['id'] = account['id']
			session['username'] = account['username']
			session['email'] = account['email']
			session['password'] = account['password']
			session['college'] = account['college']
			session['branch'] = account['branch']
			msg = 'Logged in successfully !'
			return render_template('account.html', msg = msg)
		else:
			msg = 'Incorrect username or password !'
	return render_template('login.html', msg = msg)

@app.route('/courses')
def courses():
	return render_template('courses.html')

@app.route('/contact_us')
def contact_us():
	return render_template('contact_us.html')

@app.route('/courses_cpp')
def courses_cpp():
	return render_template('courses_cpp.html')

@app.route('/courses_python')
def courses_python():
	return render_template('courses_python.html')
	
@app.route('/courses_ds')
def courses_ds():
	return render_template('courses_ds.html')
@app.route('/courses_sql')
def courses_sql():
	return render_template('courses_sql.html')

@app.route('/home')
def home():
	return render_template('home.html')

@app.route('/practice')
def practice():
	return render_template('practice.html')

@app.route('/quiz_cpp')
def quiz_cpp():
	return render_template('quiz_cpp.html')

@app.route('/quiz_python')
def quiz_python():
	return render_template('quiz_python.html')
	
@app.route('/quiz_ds')
def quiz_ds():
	return render_template('quiz_ds.html')

@app.route('/quiz_sql')
def quiz_sql():
	return render_template('quiz_sql.html')

@app.route('/tips')
def tips():
	return render_template('tips.html')

@app.route('/account')
def account():
	return render_template('account.html')

@app.route('/blog_user')
def blog_user():
	cur = mysql.connection.cursor() 
	cur.execute("""SELECT id, user, description, date, topic FROM blog WHERE user = %s""", (session['username'],))
	data = cur.fetchall()
	if (len(data) == 0):
		msg = 'You do not have any posts'
		return render_template('blog_user.html', msg = msg)
	else:
		return render_template('blog_user.html', data = data)

@app.route('/account_edit', methods=['GET','POST'])
def account_edit():
	msg=''
	if request.method == 'POST' and 'username' in request.form and 'email' in request.form and 'college' in request.form and 'branch' in request.form and 'password' in request.form:
		u = request.form['username']
		e = request.form['email']
		c = request.form['college']
		b = request.form['branch']
		p = request.form['password']
		cursor = mysql.connection.cursor(MySQLdb.cursors.DictCursor)
		cursor.execute('UPDATE user SET username = %s, email = %s, college = %s, branch = %s, password = %s WHERE username = %s', (u, e, c, b, p, session['username'], ))
		mysql.connection.commit()
		msg = 'Your details are updated successfully'
		return redirect(url_for('login'))

	elif request.method == 'POST':
		msg = 'Please fill the form!'
	return render_template('account_edit.html', msg=msg)

@app.route('/logout')
def logout():
	session.pop('loggedin', None)
	session.pop('id', None)
	session.pop('username', None)
	return redirect(url_for('login'))
	
@app.route('/feedback',methods=['GET','POST'])
def feedback():
    msg=''
    #applying empty validation
    if request.method == 'POST' and 'rating' in request.form and 'category' in request.form and 'description' in request.form:
        #passing HTML form data into python variable
        r = request.form.getlist('rating')
        c = request.form.getlist('category')
        d = request.form['description']
        cursor = mysql.connection.cursor(MySQLdb.cursors.DictCursor)
        cursor.execute('INSERT INTO FEEDBACK VALUES (NULL, % s, % s, % s)', (r, c, d,))
        mysql.connection.commit()
        msg = 'Your feedback is successfully posted !'
    elif request.method == 'POST':
        msg = 'Please fill the form!'
    return render_template('feedback.html', msg=msg)

@app.route("/blog")
def blog():
	cur = mysql.connection.cursor() 
	cur.execute("""SELECT id, user, description, date, topic FROM blog""")
	data = cur.fetchall()
	if (len(data) == 0):
		msg = 'No posts to display'
		return render_template('blog.html', msg = msg)
	else:
		return render_template('blog.html', data = data)

@app.route('/blog_add',methods=['GET','POST'])
def blog_add():
	msg=''
	if request.method == 'POST' and'description' in request.form and 'topic' in request.form:
		u = session['username']
		d = request.form['description']
		t = request.form['topic']
		date = datetime.now()
		cursor = mysql.connection.cursor(MySQLdb.cursors.DictCursor)
		cursor.execute('INSERT INTO BLOG VALUES (NULL, % s, % s, % s, %s)', (u, d, str(date), t,))
		mysql.connection.commit()
		msg = 'Your post is successfully added !'
		return redirect(url_for('blog'))
	elif request.method == 'POST':
		msg = 'Please fill the form!'
		return render_template('blog_add.html', msg=msg)
	return render_template('blog_add.html', msg=msg)

@app.route('/register', methods =['GET', 'POST'])
def register():
	msg = ''
	if request.method == 'POST' and 'username' in request.form and 'password' in request.form and 'email' in request.form and 'college' in request.form and 'branch' in request.form:
		username = request.form['username']
		password = request.form['password']
		email = request.form['email']
		college = request.form['college']
		branch = request.form['college']
		cursor = mysql.connection.cursor(MySQLdb.cursors.DictCursor)
		cursor.execute('SELECT * FROM user WHERE username = % s', (username, ))
		account = cursor.fetchone()
		if account:
			msg = 'Account already exists!'
		elif not re.match(r'[^@]+@[^@]+\.[^@]+', email):
			msg = 'Invalid email address!'
		elif not re.match(r'[A-Za-z0-9]+', username):
			msg = 'Username must contain only characters and numbers!'
		elif not username or not password or not email:
			msg = 'Please fill out the form!'
		else:
			cursor.execute('INSERT INTO user VALUES (NULL, % s, % s, % s, %s, %s)', (username, password, email, college, branch, ))
			mysql.connection.commit()
			msg = 'You have successfully registered! Login in to continue'
			return render_template('login.html', msg = msg)
	elif request.method == 'POST':
		msg = 'Please fill out the form!'
	return render_template('register.html', msg = msg)
if __name__ == "__main__":
    app.run(debug=True)
