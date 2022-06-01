<?php

declare(strict_types=1);

class ProlificUser {
  private string $prolific_subject_id;
  private string $prolific_session_id;
  private string $prolific_study_id;

  private int $num_images = 50;

  private ?int $db_user_id;
  private array $image_list = [];

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
    if ($this->db_user_id === null) {
      throw new Exception('User has not been created yet.');
    }
    return $this->db_user_id;
  }

  public function get_image_list(): array {
    return $this->image_list;
  }

  public function add_image(int $prompt_id, int $user_prompt_id, string $image_uri): void {
    $this->image_list[] = [
      'prompt_id' => $prompt_id,
      'user_prompt_id' => $user_prompt_id,
      'image_uri' => $image_uri,
    ];
  }

  public function add_images(array $images): void {
    foreach ($images as $image) {
      $this->add_image((int) $image['prompt_id'], (int) $image['user_prompt_id'], $image['image_uri']);
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

  public function get_num_images(): int {
    return $this->num_images;
  }
  
  public function set_num_images(int $num_images): void {
    $this->num_images = $num_images;
  }
  
}

?>