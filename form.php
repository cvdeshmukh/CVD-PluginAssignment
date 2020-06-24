

<div class="wp_book_box">
    <style scoped>
        .wp_book_box{
            display: grid;
            grid-template-columns: max-content 1fr;
            grid-row-gap: 10px;
            grid-column-gap: 20px;
        }
        .wp_book_field{
            display: contents;
        }
    </style>
	
	
    <p class="meta-options wp_book_field">
        <label for="wp_book_author">Author</label>
        <input id="wp_book_author" type="text" name="wp_book_author" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'wp_book_author', true ) ); ?>">
		
		
    </p>
    <p class="meta-options wp_book_field">
        <label for="wp_book_published_year">Published Year</label>
        <input id="wp_book_published_year" type="date_format" name="wp_book_published_year" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'wp_book_published_date', true ) ); ?>" >
		

		
    </p>
    <p class="meta-options wp_book_field">
        <label for="wp_book_price">Price</label>
        <input id="wp_book_price" type="number" name="wp_book_price" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'wp_book_price', true ) ); ?>">
		

    </p>
	<p class="meta-options wp_book_field">
        <label for="wp_book_publisher">Publisher</label>
        <input id="wp_book_publisher" type="text" name="wp_book_publisher" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'wp_book_author', true ) ); ?>">
		
    </p>
	<p class="meta-options wp_book_field">
        <label for="wp_book_edition">Edition</label>
        <input id="wp_book_edition" type="text" name="wp_book_edition" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'wp_book_author', true ) ); ?>">
		
    </p>
	


</div>

<script type="text/javascript">
   $(document).ready(function() {

      $('#sortable').sortable();
      $(btn_refresh).click(function(){

        document.getElementById('is1').style.display="block";
        document.getElementById('is2').style.display="block";
        document.getElementById('is3').style.display="block";

        var itemOrder = $('#sortable').sortable("toArray")

        document.getElementById("is1").src=document.getElementById(itemOrder[0]).src;
        document.getElementById("is2").src=document.getElementById(itemOrder[1]).src;
        document.getElementById("is3").src=document.getElementById(itemOrder[2]).src

        w3.slideshow(".pics", 1100);
  });
</script>

