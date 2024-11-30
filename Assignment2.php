<?php
 
$url = "https://data.gov.bh/api/explore/v2.1/catalog/datasets/01-statistics-of-students-nationalities_updated/records?where=colleges%20like%20%22IT%22%20AND%20the_programs%20like%20%22bachelor%22&limit=100";
 
$response = file_get_contents($url);
$data = json_decode($response, true);
 
 
if(!$data || !isset($data["results"])){
die('error fetching the data from API');
}
$result = $data["results"];
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
        body {
            background-color: #f8f9fa; /* Light background color */
            font-family: Arial, sans-serif; /* Font style */
            padding: 20px; /* Padding around the page */
        }
        table {
            width: 100%; /* Make the table full width */
            border-collapse: collapse; /* Remove space between cells */
            margin-top: 20px; /* Space above the table */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        th, td {
            padding: 12px; /* Padding inside cells */
            text-align: left; /* Align text to the left */
            border-bottom: 1px solid #ddd; /* Bottom border for rows */
        }
        th {
            background-color: #007bff; /* Header background color */
            color: white; /* Header text color */
        }
        tr:hover {
            background-color: #f1f1f1; /* Highlight row on hover */
        }
        @media (max-width: 600px) {
            table {
                font-size: 14px; /* Adjust font size for smaller screens */
            }
        }
    </style>
</head>
<body>
<div class="container">
<h1>University of Bahrain Students Enrollment by Nationality</h1>
<table>
<thead>
<tr>
<th>Year</th>
<th>Semester</th>
<th>The programs</th>
<th>Nationality</th>
<th>Colleges</th>
<th>Number Of Students</th>
</tr>
</thead>
<tbody>
<?php
foreach($result as $student) {
?>
<tr>
<td><?php echo $student["year"]; ?></td>
<td><?php echo $student["semester"]; ?></td>
<td><?php echo $student["the_programs"]; ?></td>
<td><?php echo $student["nationality"]; ?></td>
<td><?php echo $student["colleges"]; ?></td>
<td><?php echo $student["number_of_students"]; ?></td>
</tr>
<?php
}
?>
</tbody>
</table>
</div>
</body>
</html>