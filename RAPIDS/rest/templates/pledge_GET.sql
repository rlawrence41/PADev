select 	pledge.pledge_no, pledge.id, user.user_id as idSearch, 
	pledge.project_no, project.project_id as project_noSearch, project.estimated_cost,
	pledge.amount, pledge.comment
  from pledge left join user ON user.id = pledge.id 
  left join project ON project.project_no = pledge.project_no
  %where% %orderBy% %limit% ;