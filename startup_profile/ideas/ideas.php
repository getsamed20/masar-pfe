
<!-- Add Idea Button -->
<div class="mb-4 text-end">
    <button class="btn btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#addIdeaModal">
        + Propose Idea
    </button>
</div>

<!-- Add Idea Modal -->
<div class="modal fade" id="addIdeaModal" tabindex="-1" aria-labelledby="addIdeaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="ideas/add_idea.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addIdeaModalLabel">Propose a New Idea</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>

                    <label class="form-label mt-3">Description</label>
                    <textarea name="description" class="form-control" rows="4" required></textarea>

                    <label class="form-label mt-3">Attachments (images/videos)</label>
                    <input type="file" name="media[]" class="form-control" multiple>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit Idea</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- My Ideas Section -->
<div class="mb-5">
    <h4>My Ideas</h4>
    
    <?php
    $ideas = mysqli_query($conn, "SELECT * FROM ideas WHERE startup_id = '$startup_id' ORDER BY created_at DESC");

    while ($i = mysqli_fetch_assoc($ideas)) :
        $idea_id = $i['idea_id'];
        $media_query = mysqli_query($conn, "SELECT * FROM media WHERE idea_id = '$idea_id'");
        $images = $videos = [];

        while ($m = mysqli_fetch_assoc($media_query)) {
            if ($m['media_type'] === 'image') {
                $images[] = $m['file_path'];
            } elseif ($m['media_type'] === 'video') {
                $videos[] = $m['file_path'];
            }
        }
    ?>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mt-2"><?= htmlspecialchars($i['title']) ?></h5>
                <p><?= nl2br(htmlspecialchars($i['description'])) ?></p>

                <!-- Image Carousel -->
                <?php if (!empty($images)) :
                    $carouselId = "carouselIdea" . $idea_id; ?>
                    <div id="<?= $carouselId ?>" class="carousel slide mb-3" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($images as $index => $img) : ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <img src="../uploads/<?= $img ?>" class="d-block w-100 rounded">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($images) > 1) : ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Video Previews -->
                <?php foreach ($videos as $vid) : ?>
                    <video controls class="w-100 rounded mb-2">
                        <source src="../uploads/<?= $vid ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php endforeach; ?>

                <!-- Actions -->
                <div class="mt-3 d-flex justify-content-end gap-2">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editIdeaModal<?= $idea_id ?>">Edit</button>
                    <a href="ideas/delete_idea.php?id=idea_<?= $idea_id ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this idea?')">Delete</a>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editIdeaModal<?= $idea_id ?>" tabindex="-1" aria-labelledby="editIdeaModalLabel<?= $idea_id ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="ideas/edit_idea.php" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editIdeaModalLabel<?= $idea_id ?>">Edit Idea</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="idea_id" value="<?= $idea_id ?>">

                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($i['title']) ?>" required>

                            <label class="form-label mt-3">Description</label>
                            <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($i['description']) ?></textarea>

                            <div class="alert alert-info mt-3">
                                To change media, please delete this idea and create a new one.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php endwhile; ?>
</div>

