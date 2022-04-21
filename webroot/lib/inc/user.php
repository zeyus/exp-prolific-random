<?php

declare(strict_types=1);

class ProlificUser {
  private final string $prolific_subject_id;
  private final string $prolific_session_id;
  private final string $prolific_study_id;

  private final ?int $db_user_id;
  private final array $image_list = [];

  public function __construct(string $prolific_subject_id, string $prolific_session_id, string $prolific_study_id) {
    $this->set_prolific_subject_id($prolific_subject_id);
    $this->set_prolific_session_id($prolific_session_id);
    $this->set_prolific_study_id($prolific_study_id);
  }

  // validate a-z0-9 string
  public function validate_alphanumeric(string $prolific_id): bool {
    return preg_match('/^[a-z0-9]+$/i', $prolific_id) === 1;
  }

  // getters
  public function get_prolific_subject_id(): string {
    return $this->prolific_subject_id;
  }

  public function get_prolific_session_id(): string {
    return $this->prolific_session_id;
  }

  public function get_prolific_study_id(): string {
    return $this->prolific_study_id;
  }

  public function get_db_user_id(): int {
    return $this->db_user_id;
  }

  public function get_image_list(): array {
    return $this->image_list;
  }

  public function add_image($id, $uri): void {
    $this->image_list[] = [
      'id' => $id,
      'uri' => $uri,
    ];
  }

  public function add_images($images): void {
    foreach ($images as $image) {
      $this->add_image($image['id'], $image['uri']);
    }
  }

  // setters with validation
  public function set_prolific_subject_id(string $prolific_subject_id): void {
    if ($this->validate_alphanumeric($prolific_subject_id)) {
      $this->prolific_subject_id = $prolific_subject_id;
    } else {
      throw new Exception('Invalid prolific subject id.');
    }
  }

  public function set_prolific_session_id(string $prolific_session_id): void {
    if ($this->validate_alphanumeric($prolific_session_id)) {
      $this->prolific_session_id = $prolific_session_id;
    } else {
      throw new Exception('Invalid prolific session id.');
    }
  }

  public function set_db_user_id(int $id): void {
    $this->db_user_id = $id;
  }

  public function set_prolific_study_id(string $prolific_study_id): void {
    if ($this->validate_alphanumeric($prolific_study_id)) {
      $this->prolific_study_id = $prolific_study_id;
    } else {
      throw new Exception('Invalid prolific study id.');
    }
  }


  
}

?>