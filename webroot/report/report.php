<?php 

?>
<!-- summary of image ratings -->
<div class="row">
    <div class="col-md-12">
        <h1>Image Ratings</h1>
    </div>
    <div class="col-md-12">
        <p><?php echo count($ratings); ?> images shown.</p>
        <p>Only images which have been prepared for a participant, or have subsequently been rated are included.</p>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Times rated</th>
                    <th>Times prepared</th>
                    <th>Creativity (mean)</th>
                    <th>Abstract (mean)</th>
                    <th>Symmetric (mean)</th>
                    <th>Response time (seconds, mean)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ratings as $rating) { ?>
                    <tr>
                        <td><?php echo $rating['prompt_id']; ?></td>
                        <td><?php echo $rating['times_rated']; ?></td>
                        <td><?php echo $rating['times_prepared']; ?></td>
                        <td><?php echo empty($rating['avg_rating_creative']) ? '-' : number_format($rating['avg_rating_creative'], 1); ?></td>
                        <td><?php echo empty($rating['avg_rating_abstract']) ? '-' : number_format($rating['avg_rating_abstract'], 1); ?></td>
                        <td><?php echo empty($rating['avg_rating_symmetry']) ? '-' : number_format($rating['avg_rating_symmetry'], 1); ?></td>
                        <td><?php echo empty($rating['avg_rt']) ? '-' : number_format($rating['avg_rt'] / 1000, 2); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>