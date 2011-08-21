<div class="typography">
	<% if Menu(2) %>
		<% include SideBar %>
		<div id="Content">
	<% end_if %>

	<% if Level(2) %>
	  	<% include BreadCrumbs %>
	<% end_if %>
	
		<h2>$Title</h2>
	
		$Content
		
		<p><a href="$Link?month=-1"><% _t('Calendar.ss.PREVIOUS', 'Previous') %></a> | <a href="$Link?month=1"><% _t('Calendar.ss.NEXT', 'Next') %></a> | <a href="$Link?month=0"><% _t('Calendar.ss.CURRENT', 'Current') %></a></p>
		<table class="calendar">
			<tr>
				<th colspan="7">$MonthName</th>
			</tr>
			<tr>
				<th><% _t('Calendar.ss.SUNDAY', 'Sun') %></th>
				<th><% _t('Calendar.ss.MONDAY', 'Mon') %></th>
				<th><% _t('Calendar.ss.TUESDAY', 'Tue') %></th>
				<th><% _t('Calendar.ss.WEDNESDAY', 'Wed') %></th>
				<th><% _t('Calendar.ss.THURSDAY', 'Thu') %></th>
				<th><% _t('Calendar.ss.FRIDAY', 'Fri') %></th>
				<th><% _t('Calendar.ss.SATURDAY', 'Sat') %></th>
			</tr>
			<% control Weeks %><tr>
				<% control Days %><td class="$OddEven">$Day
					<% control Events %><div class="event">$Event</div><% end_control %>
				</td>
				<% end_control %> 
			</tr>
			<% end_control %>
		</table>

		$PageComments

	<% if Menu(2) %>
		</div>
	<% end_if %>
</div>