<div class="typography">

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
					<% control Events %><div class="event"><% if Link %><a href="$Link">$Event</a><% else %>$Event<% end_if %></div><% end_control %>
				</td>
				<% end_control %> 
			</tr>
			<% end_control %>
		</table>

</div>