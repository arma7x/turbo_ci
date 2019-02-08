var _user = <?php echo json_encode($this->container['user']) ?>;
var avatar = $('#avatar');
if (avatar.length != 0) {
    avatar.attr('src', _user.avatar)
}

