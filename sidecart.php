<div class="col-4 right" id ="sidecart">
		<h3>Your Cart</h3>
		<?php
		//cancel order button
		echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
		echo '<div class ="cartbtn"><input type="submit" name="ClearCart" value="Clear Cart"></div>';
		echo '</form>'; 
		
		if(isset($message)) { echo '<p class="message">' . $message . '</p>'; } ?>
		<table>
			<?php

			if(isset($_SESSION['cartproducts'])) {
				//loops through each item in the cart products array
				//$key holds the array index
				//$orderitem holds the array values for the $key
				foreach ($_SESSION['cartproducts'] as $key => $orderitem) {
				
				//displays the product name and price
				echo '<tr><td><div id = "prodname">' . $orderitem['productname'] . '&nbsp;&nbsp;$' . $orderitem['productprice'] . '</td>';
				echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
				echo '<input type = "hidden" name = "orderitem" value = "'. $key .'">';
				echo '</form><td>';

				//displays the remove button
				echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
				echo '<input type = "hidden" name = "orderitem" value = "'. $key .'">';
				echo '<div id ="remove"><input type="submit" name="RemoveItem" value="Remove"></div>';
				echo '</form></td></tr>';

				//displays the notes entry
				echo '<tr><td class="notesection"><form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
				echo '<input type = "hidden" name = "orderitem" value = "'. $key .'">';

				echo '<input type="text" name="notes" id="note" value="' . $orderitem['productnotes'] . '"" placeholder="Notes for the item"></td>';

				echo '<td class="notesection"><div id="notes"><input type="submit" name="NoteEntry" value="Add Note" id="noteentry"></div>';
				echo '</form></td></tr>';
				
				}
			}
				if($_SESSION['cart']['ordertotal'] > 0) {
			?>
			<tr><td>Subtotal:</td><td><?php echo '$' . $_SESSION['cart']['ordertotal']; ?></td></tr>
			<?php } ?>
		</table>

		
	
<?php
	//checkout button
	echo '<form action = "cart.php" method = "post">';
	echo '<div class ="button2"><input type="submit" name="CompleteOrder" value="Check Out"></div>';
	echo '</form>';
?>	
	</div><!--Ends sidebar-->