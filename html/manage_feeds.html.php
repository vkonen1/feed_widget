<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="css/manage_feeds.css" />
</head>
<body>
    <h1><?php echo $page_title; ?></h1>
    <p>Please ensure the absolute path to the rss feed is provided.</p>
    <form action="add_feed.php" method="post">
        <input type="text" name="feed_url" />
        <input type="submit" value="Add Feed" />
    </form>
    <?php if ($num_feeds == 0) { ?>
        <p>There are no feeds in the database.</p>
    <?php } else { ?>
        <table>
            <tr>
                <th>Feed ID</th>
                <th>Feed URL</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($feeds as $feed) {
                ?>
                <tr>
                    <td><?php echo $feed['feed_id']; ?></td>
                    <td><?php echo $feed['feed_url']; ?></td>
                    <td><a href="remove_feed.php?feed_id=<?php echo $feed['feed_id']; ?>">Remove</a></td>
                </tr>
                <?php
            } ?>
        </table>
    <?php } ?>
</body>
</html>