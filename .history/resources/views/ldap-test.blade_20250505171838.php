<!DOCTYPE html>
<html>
<head>
    <title>LDAP Test</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <h1>Danh sách Users từ LDAP</h1>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Display Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user['username'] }}</td>
                <td>{{ $user['displayname'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 