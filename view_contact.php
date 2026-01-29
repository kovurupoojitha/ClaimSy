<?php
include 'config.php';

// TODO: add admin-login guard here

$result = $conn->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Messages | Admin Panel</title>

    <style>
        /* ────────── Base layout ────────── */
        :root{
            --bg         : #f9fafb;
            --card       : #ffffff;
            --text       : #111827;
            --accent     : #2563eb;
            --border     : #e5e7eb;
        }
        /* Dark-mode palette (toggled with JS) */
        .dark{
            --bg     : #111827;
            --card   : #1f2937;
            --text   : #f3f4f6;
            --accent : #3b82f6;
            --border : #374151;
        }

        *{ box-sizing:border-box; font-family: 'Segoe UI',Roboto,Helvetica,Arial,sans-serif; }

        body{
            margin:0;
            background:var(--bg);
            color:var(--text);
            min-height:100vh;
            display:flex;
            flex-direction:column;
            padding:32px;
        }

        h2{
            margin:0 0 16px;
            font-size:1.5rem;
            display:flex;
            align-items:center;
            gap:16px;
        }

        /* ────────── Dark-mode toggle button ────────── */
        .toggle{
            margin-left:auto;
            cursor:pointer;
            padding:6px 12px;
            border-radius:6px;
            border:1px solid var(--border);
            background:var(--card);
            color:var(--text);
            transition:background .2s, color .2s;
        }
        .toggle:hover{ background:var(--accent); color:#fff; }

        /* ────────── Table styling ────────── */
        .table-wrapper{
            overflow-x:auto;   /* allow horizontal scroll on mobile */
        }
        table{
            width:100%;
            border-collapse:collapse;
            background:var(--card);
            border:1px solid var(--border);
            border-radius:8px;
            overflow:hidden;
        }
        th,td{
            padding:12px 16px;
            border-bottom:1px solid var(--border);
            text-align:left;
            vertical-align:top;
            font-size:.95rem;
        }
        th{
            background:var(--bg);
            font-weight:600;
        }
        tr:last-child td{ border-bottom:none; }

        /* Zebra stripes */
        tbody tr:nth-child(even){ background:rgba(0,0,0,.03); }
        .dark tbody tr:nth-child(even){ background:rgba(255,255,255,.03); }

        /* Responsive tweaks */
        @media (max-width:600px){
            th,td{ white-space:nowrap; }
            body{ padding:16px; }
        }
    </style>
</head>

<body>
    <h2>
        Contact Form Submissions
        <button class="toggle" id="modeToggle">Toggle Dark Mode</button>
    </h2>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th style="min-width:200px;">Message</th>
                    <th>Time</th>
                </tr>
            </thead>

            <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= htmlspecialchars($row["name"]) ?></td>
                    <td><?= htmlspecialchars($row["email"]) ?></td>
                    <td><?= htmlspecialchars($row["subject"]) ?></td>
                    <td><?= nl2br(htmlspecialchars($row["message"])) ?></td>
                    <td><?= $row["submitted_at"] ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        /* Simple dark-mode toggle (stores choice in localStorage) */
        const root = document.documentElement;
        const btn  = document.getElementById('modeToggle');

        // on load
        if (localStorage.getItem('theme') === 'dark') root.classList.add('dark');

        btn.addEventListener('click', () => {
            root.classList.toggle('dark');
            localStorage.setItem('theme', root.classList.contains('dark') ? 'dark' : 'light');
        });
    </script>
</body>
</html>
