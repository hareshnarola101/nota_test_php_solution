<?php
/**
 * Class TableCreator - A class for creating and managing the Test table.
 */
final class TableCreator
{
    private $pdo;

    /**
     * Constructor that executes the create and fill methods.
     */
    public function __construct()
    {
        // Initialize a PDO instance for database connection
        $this->pdo = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
        $this->create();
        $this->fill();
    }

    /**
     * Create the Test table with the specified fields.
     */
    private function create()
    {
        $query = "CREATE TABLE IF NOT EXISTS Test (
            id INT AUTO_INCREMENT PRIMARY KEY,
            script_name VARCHAR(25),
            start_time DATETIME,
            end_time DATETIME,
            result ENUM('normal', 'illegal', 'failed', 'success')
        )";
        $this->pdo->exec($query);
    }

    /**
     * Fill the Test table with random data.
     */
    private function fill()
    {
        // You can generate and insert random data using any method or library you prefer.
        // This example inserts sample data.
        for ($i = 0; $i < 10; $i++) {
            $scriptName = 'Script ' . $i;
            $startTime = date('Y-m-d H:i:s', strtotime('-' . $i . ' days'));
            $endTime = date('Y-m-d H:i:s');
            $result = ['normal', 'illegal', 'failed', 'success'][rand(0, 3)];

            $stmt = $this->pdo->prepare("INSERT INTO Test (script_name, start_time, end_time, result) VALUES (?, ?, ?, ?)");
            $stmt->execute([$scriptName, $startTime, $endTime, $result]);
        }
    }

    /**
     * Select data from the Test table based on the result criterion.
     *
     * @param string $criterion The result criterion ('normal' or 'success')
     * @return array Selected data from the Test table
     */
    public function get($criterion)
    {
        $query = "SELECT * FROM Test WHERE result IN ('normal', 'success')";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Example usage:
$tableCreator = new TableCreator();
$data = $tableCreator->get('normal');
print_r($data);

?>