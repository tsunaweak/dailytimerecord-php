<h2>Simple Daily Time Record using PHP </h2>

<h3>How to use. </h3>
<ol>
  <li>Download the file and extract.</li>
  <li>Put the extracted file on the web root directory.</li>
  <li>Change the database credentials on "/dtr/app/Models/Models.php".</li>
  <li>Import the dtr.sql on the database.</li>
</ol>

<h3> Terminologies </h3>
<ol>
  <li>Spent Time - This is the <strong>Difference</strong> of login time and logout time.</li>
  <li>Deduction Time - This is used to auto deduct the spent time <strong>(Default 0 in hour)</strong>.</li>
  <li>Time After Login - This is the time interval to the trainee to logout after login <strong>(Default 3 hours)</strong>.</li>
  <li>Time After Logout - This is the time interval to the trainee to login after Logout <strong>(Default 3 hours)</strong>.</li>
</ol>

<h3> Default Credentials </h3>
<div><strong>username: admin</strong></div>
<div><strong>password: admin</strong></div>
<h3>Features</h3>
<ul>
  <h4>Admin</h4>
  <li>
    <ul> 
      <h4>Main Page</h4>
      <li>Adding Trainee</li>
      <li>Update Trainee</li>
      <li>Delete Trainee</li>
      <li>View records of a trainee</li>
      <li>Delete records of a trainee</li>
      <li>Update records of a trainee</li>
    </ul>
  </li>
  <li>
    <ul> 
      <h4>Event Page</h4>
      <li>Adding Event</li>
      <li>Update Event</li>
      <li>Delete Event</li>
      <li>Type of Event
      <li>Double Time</li>
      <li>Additional Time</li>
      </li>
    </ul>
  </li>
  <li>
    <ul> 
      <h4>Other Page</h4>
      <li>Set Deduction Time</li> 
      <li>Set Time after login</li>
      <li>Set Time after logout</li>
      <li>Set Time after logout</li>
      <li>Change Account Credentials</li>
      <li>Single Insert Trainee Record</li>
      <li>Insert Trainee Records via Excel</li>
    </ul>
  </li>
</ul>
<ul>
  <h4>Trainee</h4>
    <li>
      <ul>
        <li>Login and Logout</li>
        <li>View Records</li>
        <li>Export Records</li>
      </ul>
    </li>
</ul>

