<div class="content">
  <h1><?php echo $translations['my_lists'] ?? 'My Lists'; ?></h1>
  
  <div class="category-buttons">
    <!-- Category buttons for filtering anime -->
    <button class="toggle-btn active" data-category="Watching">Watching</button>
    <button class="toggle-btn" data-category="Completed">Completed</button>
    <button class="toggle-btn" data-category="OnHold">On Hold</button>
  </div>

  <div class="anime-list" id="anime-list">
    <!-- Anime items will be dynamically inserted here by JavaScript -->
  </div>

  <div class="pagination" id="pagination">
    <!-- Pagination links will be dynamically inserted here by JavaScript -->
  </div>
</div>
