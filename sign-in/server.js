const express = require('express');
const app = express();
const { Pool } = require('pg');
const path = require('path');

// PostgreSQL connection pool
const pool = new Pool({
  user: 'admin', // Replace with your PostgreSQL username
  host: 'localhost',
  database: 'users', // Replace with your database name
  password: 'admin', // Replace with your PostgreSQL password
  port: 3000, // Default PostgreSQL port
});

// Middleware
app.use(express.urlencoded({ extended: true }));
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');
app.use(express.static(path.join(__dirname, 'static')));

// Routes
app.get('/', (req, res) => {
  res.render('sign-in');
});

app.post('/submit-form', async (req, res) => {
  const { name, email, phone, userid, password, confirm_password } = req.body;

  // Check if passwords match
  if (password !== confirm_password) {
    return res.send('Passwords do not match!');
  }

  try {
    // Connect to the database
    const client = await pool.connect();

    // Insert form data into the database
    const query = 'INSERT INTO users (name, email, number, username, password) VALUES ($1, $2, $3, $4, $5) RETURNING *';
    const values = [name, email, phone, userid, password];
    const result = await client.query(query, values);

    // Release the database connection
    client.release();

    res.send('Form submitted successfully!');
  } catch (err) {
    console.error(err);
    res.status(500).send('Error submitting form');
  }
});

const PORT = process.env.PORT || 3000; // Changed to port 3001
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});
