<!DOCTYPE html>
<head>
    <title>Ticket Reports</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style type="text/css">
        table tr td,
		table tr th{
			font-size: 9pt;
		}

      #judul{
          text-align:center;
      }

      #halaman{
          width: auto; 
          height: auto; 
          position: absolute; 
          padding-left: 5px; 
          padding-right: 5px; 
          padding-bottom: 5px;
          font-family: 'MS GOTHIC', Courier, monospace;
      }
    </style>
</head>

<body>
    <div id=halaman>
        <h5 id=judul >Ticket Reports</h5><br>

        <table class="table table-bordered" width="100%">
            <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Requester</th>
                    <th>Location</th>
                    <th>Office</th>
                    <th>Technician</th>
                    <th>Assign Date</th>
                    <th>Solved Date</th>
                    <th>Duration</th>
                    <th>Resolution</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $data)
                @php
                    $dates = new DateTime($data->date);
                    $assignDate = new DateTime($data->assign_date);
                    $solvedDate = new DateTime($data->solved_date);
                    $interval = $assignDate->diff($solvedDate);
                    $year = $interval->y;
                    $month = $interval->m;
                    $day = $interval->d;
                    $hour = $interval->format('%H');
                    $minute = $interval->format('%I');
                    $second = $interval->format('%S');

                    $assign_date = $assignDate->format('d M Y h:i:s');
                    $solved_date = $solvedDate->format('d M Y h:i:s');
                    $date = $dates->format('d M Y');

                    if ($year != 0) {
                        $ticketTime = $year . 'y ' . $month . 'm ' .   $day . 'd ' . $hour . ':' . $minute . ':' . $second;
                    } elseif ($month != 0) {
                        $ticketTime = $month . 'm ' .   $day . 'd ' . $hour . ':' . $minute . ':' . $second;
                    } else if ($day != 0) {
                        $ticketTime = $day . 'd ' . $hour . ':' . $minute . ':' . $second;
                    } else {
                        $ticketTime = $hour . ':' . $minute . ':' . $second;
                    }   
                @endphp
                    <tr>
                        <td>#{{ $data->nomor }}</td>
                        <td>{{ $date}}</td>
                        <td>{{ $data->title }}</td>
                        <td>{{ $data->category->name }}</td>
                        <td>{{ $data->userRequester->name }}</td>
                        <td>{{ $data->location->name }}</td>
                        <td>{{ $data->office->name }}</td>
                        <td>{{ $data->userTechnician ? $data->userTechnician->name : '' }}</td>
                        <td>{{ $data->assign_date ? $assign_date : '' }}</td>
                        <td>{{ $data->solved_date ? $solved_date : '' }}</td>
                        <td>{{ $ticketTime }}</td>
                        @foreach ($data->ticketProgress as $progress)
                            <td>{{ $progress->description }}</td>
                        @endforeach
                        <td>{{ $data->status ? $data->status : '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</body>

</html>