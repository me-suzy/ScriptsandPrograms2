function sterge(id) {
	if(confirm("Are you sure you want to erase this comment?")){
		window.location='index.php?page=guestbook&act=delete&id=' + id;
	}
}
