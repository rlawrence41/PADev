-- This view is designed to simplify and speed up queries for item totals.

DROP VIEW if exists itemTotal;
CREATE SQL SECURITY INVOKER VIEW itemTotal AS 
select id, orderKey, lTaxable,
sum(quantity) as quantity, 
ROUND(sum(quantity*(price-deduction)), 2) as subtotal, 
sum(quantity*shipWeight) as shipWeight 
from orderItem 
group by orderKey, lTaxable;