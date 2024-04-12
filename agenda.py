import json

def load_agenda(file_path):
    try:
        with open(file_path, 'r') as file:
            data = json.load(file)
        return data
    except FileNotFoundError:
        print(f"File not found: {file_path}")
        return []
    except json.JSONDecodeError:
        print("Error decoding JSON.")
        return []

def generate_html(data):
    html_content = """
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Online Agenda</title>
    </head>
    <body>
        <h1>Appointments</h1>
        <ul>
    """

    for appointment in data:
        html_content += f"""
            <li>
                <strong>Date:</strong> {appointment.get('date', 'N/A')} <br>
                <strong>Time:</strong> {appointment.get('time', 'N/A')} <br>
                <strong>Name:</strong> {appointment.get('name', 'N/A')} <br>
                <strong>Email:</strong> {appointment.get('email', 'N/A')}
            </li>
        """

    html_content += """
        </ul>
    </body>
    </html>
    """

    with open('agenda.html', 'w') as file:
        file.write(html_content)

def main():
    file_path = 'appointments.json'  # Replace with your JSON file path
    agenda_data = load_agenda(file_path)
    if agenda_data:
        generate_html(agenda_data)
    else:
        print("No agenda data available.")

if __name__ == "__main__":
    main()
