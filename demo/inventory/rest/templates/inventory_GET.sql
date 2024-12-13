select inventory.id, inventory.transDate, inventory.itemNo, inventory.titleNo, 
inventory.titleId, inventory.itemCondtn, inventory.invState, inventory.location, 
concat_ws(",", c.lastName, c.firstName, c.company) AS locationSearch, 
inventory.shipmentNo, inventory.receiptNo, inventory.itemReceiptNo, 
inventory.quantity, inventory.updatedBy, inventory.userNo, inventory.lastUpdated 
from inventory LEFT JOIN contact c ON c.id = location %where% %orderBy% %limit% ;
