<?php


class JsonDB {
  private array $db;
  private string $dbFilePath;

  function __construct(string $dbName) {
    $basePath = __DIR__ . '/../db';

    $dbFiles = [
      'beneficiaries' => $basePath . '/beneficiaries.json',
      'plans' => $basePath . '/plans.json',
      'prices' => $basePath . '/prices.json'
    ];

    $this->dbFilePath = $dbFiles[$dbName] ?? error('DB not found.');
    $this->db = _json_decode(file_get_contents($this->dbFilePath)) ?? [];
  }

  /**
   * Retorna todos os dados da DB
   */
  public function selectAll(): array {
    return $this->db;
  }

  /**
   * Retorna dados da DB filtrados por chave e valor
   */
  public function selectWhere($key, $value): array {
    return array_filter($this->db, fn ($item) => $item[$key] == $value);
  }

  /**
   * Atualiza um dado na DB
   * O dado deve ser inserido como um array, que será concatenado ao restante da DB
   */
  public function update(array $newArray): void {
    $this->db = array_merge($this->db, $newArray);
  }

  /**
   * Salva as alterações na DB
   */
  public function save(): void {
    $json = _json_encode($this->db);
    file_put_contents($this->dbFilePath, $json);
  }
}
