<!DOCTYPE html>
<html>
<head>
    <title>Solr Search Engine</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom, #e0e0e0, #ffffff);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 80%;
            max-width: 600px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
            box-sizing: border-box;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        header {
            margin-bottom: 30px;
        }
        h1 {
            color: #333;
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }
        .search-bar {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        .search-bar::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 102, 204, 0.1);
            border-radius: 24px;
            z-index: -1;
            transform: scale(1.1);
            transition: transform 0.3s;
        }
        .search-bar:hover::before {
            transform: scale(1.2);
        }
        .search-bar input {
            width: 70%;
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 30px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .search-bar input:focus {
            border-color: #0066cc;
            box-shadow: 0 4px 8px rgba(0, 102, 204, 0.2);
            background-color: #ffffff;
        }
        .search-bar button {
            padding: 12px 25px;
            border: none;
            border-radius: 30px;
            background: linear-gradient(to right, #0066cc, #004d99);
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-left: 15px;
            transition: background 0.3s, transform 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .search-bar button:hover {
            background: linear-gradient(to right, #004d99, #003366);
            transform: scale(1.05);
        }
        .search-bar button:active {
            background: #003366;
            transform: scale(1);
        }
    </style>
</head>
<body>
<div class="container">
    <form action="search.php" method="GET">
        <header>
            <h1>Advanced Search</h1>
        </header>
        <main>
            <div class="search-bar">
                <input type="text" name="q" placeholder="What are you looking for?">
                <button type="submit">Search</button>
            </div>
        </main>
    </form>
</div>
</body>
</html>
