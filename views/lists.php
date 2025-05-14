<div class="content">
  <h1><?php echo $translations['my_lists'] ?? 'My Lists'; ?></h1>
  
  <div class="category-buttons">
    <button class="toggle-btn active" data-category="Watching"><?php echo $translations['watching'] ?? 'Watching'; ?></button>
    <button class="toggle-btn" data-category="Completed"><?php echo $translations['completed'] ?? 'Completed'; ?></button>
    <button class="toggle-btn" data-category="Dropped"><?php echo $translations['dropped'] ?? 'Dropped'; ?></button>
  </div>

  <div class="anime-list" id="anime-list">
  </div>

  <div class="pagination" id="pagination">
  </div>
</div>
