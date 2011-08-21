	<div id="Content">
		<div class="c12">
			<div id="LeftColumn" class="g3l">
<% include SideMenu %>
<% include SideContent %>
			</div>
			 <div class="g9r typography">
				<div id="Breadcrumbs" class="gr9">
					<p><a href="/">Home</a> » $Breadcrumbs</p>
				</div>
<% include TitleBackground %>
				$Content
				<table class="calendar">
					<tr>
						<th colspan="7">$CurrentMonth</th>
					</tr>
					<tr>
						<th>Sun</th>
						<th>Mon</th>
						<th>Tue</th>
						<th>Wed</th>
						<th>Thu</th>
						<th>Fri</th>
						<th>Sat</th>
					</tr>
					<% control Weeks %><tr>
						<% control Days %><td class="$OddEven">$Day
							<% control Events %><div class="event">$Event</div><% end_control %>
						</td>
						<% end_control %> 
					</tr>
					<% end_control %>
				</table>
			</div>
		</div>
		<br class="c" />
	</div>