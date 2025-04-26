<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <style>

        body {
            background-color: #CCE5FF;
        }

        .navbar {
            display: flex;
            justify-content: space-between; /* pushes left + right apart */
            align-items: center;
            padding: 20px;
            background-color: white;
        }

        /* Nav bar items style */
        .nav-items a {
            color: #003366;
            text-decoration: none;
        }

        .nav-items {
            display: flex;
            gap: 20px;
        }

        /*"ChoreForce" style in nav bar*/
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #003366;
        }

        /*subheading style*/
        h2 {
            font-size: 32px;
            color: #003366;
            margin-top: 100px;
            text-align: center;
        }

        /*form label style*/
        label, p {
            font-size: 24px;
            font-weight: bold;
            color: #4967ad;
            margin-left: 30px;
        }

        /*form input boxes style*/
        input, textarea {
            margin-top: 10px;
            margin-left: 30px;
            font-size: 24px;
        }

        /*form submit button style*/
        .addchore-btn {
            width: 25%;
            padding: 12px;
            border-radius: 5px;
            font-size: 18px;
            margin-top: 15px;
            background: #4967ad;
            color: white;
        }
    </style>
</head>
<body>
    <nav>
        <div class="navbar">
            <!--nav bar-->
            <div class="logo">ChoreForce</div>
            <div class="nav-items">
                <a href="parentportal.html">My Portal</a>   <!--linked to parent portal for now, but this would change based on whether user is parent or child(childportal.html)-->
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <h2>Add New Chore</h2>

    <!--for phase 4, add link to php page below-->
    <form action="addchore1.php" method="post">

        <p>Who is this chore for?</p>

        <?php include 'addchore2.php'; ?>

        <!-- for phase 3 purposes, fake children are shown. change this to fit accurate data in phase 4-->

        <!--<input type="radio" id="child_1" name="chore_assign" value="child_1">
        <label for="child_1">Child 1</label><br>

        <input type="radio" id="child_2" name="chore_assign" value="child_2">
        <label for="child_2">Child 2</label><br>

        <input type="radio" id="child_3" name="chore_assign" value="child_3">
        <label for="child_3">Child 3</label><br>
        -->

        <br><br>
        <label for="chore">Chore:</label> <br>
        <!-- I'm not sure if chore desc is detailed like sentences or just 1-2 words so the input is a large textbox for now-->
        <textarea id="choredesc" name="choredesc" rows="4" cols="50" placeholder="Describe the chore" required></textarea> <br> <br>

        <label for="reward">Reward Amount:</label> <br>
        <input type="text" id="reward" name="reward" class="input-field"
               pattern="[0-9]*" title="enter the dollar value do not include '$' or cents"
               placeholder="Enter the reward amount" required> <br> <br>
        <p style="text-align: center">
            <button type="submit" class="addchore-btn">Add Chore</button>
        </p>

    </form>

</body>
</html>
