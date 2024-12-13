select auth.id, auth.user_id, auth.contact_no,
	concat(contact.lastName, ", ", contact.firstName) as contact_noSearch,
	auth.email, auth.phone, auth.authCode, auth.password
  from user as auth
  left join contact on contact.id = auth.contact_no
  %where% %orderBy% %limit%;
