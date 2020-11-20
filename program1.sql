-- Author: Alvin J
-- Assign: Program 1
-- Url: http://web.engr.oregonstate.edu/~johnsal/cs340/index.php
--
-- Desc: performs inserts, updates, deletes, and checks against
--       tables DEPT_STATS, EMPLOYEE, WORKS_ON
-- NOTES: Before performing project additions, the insertion,
--        deletions, and updates to an employee works. After the 
--        insertion of a project for the specified employee, the 
--        employee is not able to be deleted. phpmyadmin tells me 
--        that a foreign key contraint was not handled. Since this 
--        portion was not part of the assignment, I left it for 
--        later. I hope that was correct.
--
-------------------------------------------------------------------
--SQL CODE
--
-- initializing procedure
BEGIN
INSERT INTO DEPT_STATS
	SELECT 
    Dnumber, 
    COUNT(*),
    AVG(Salary)
    FROM DEPARTMENT, EMPLOYEE
    WHERE DEPARTMENT.Dnumber = Employee.Dno
    GROUP BY Dnumber;
END
-------------------------------------------------------------------
--
-- create the blank table
CREATE TABLE DEPT_STATS (
  Dnumber int(2) NOT NULL,
  Emp_count int(11) NOT NULL,
  Avg_salary decimal(10,2) NOT NULL,
  PRIMARY KEY(Dnumber)
)
-------------------------------------------------------------------
--
-- Triggers for: insert, update, and delete on the employee table
--
-- Delete a tuple
BEGIN
	IF OLD.Dno IS NOT NULL THEN
    	UPDATE DEPT_STATS
        SET Emp_count = Emp_count - 1
        WHERE DEPT_STATS.Dnumber = OLD.Dno;
    END IF;
    
 	IF OLD.Dno IS NOT NULL THEN
    	UPDATE DEPT_STATS 
        SET Avg_salary = (select AVG(Salary)
                          from EMPLOYEE
                          where EMPLOYEE.Ssn != OLD.Ssn 
                          and EMPLOYEE.Dno = OLD.Dno)
        WHERE DEPT_STATS.Dnumber = OLD.Dno;
   	END IF;
END
-------------------------------------------------------------------
--
-- Insert a tuple 
BEGIN
	IF NEW.Dno IS NOT NULL THEN
    	UPDATE DEPT_STATS
		SET Emp_count = Emp_count + 1
        	WHERE DEPT_STATS.Dnumber = NEW.Dno;
    END IF;
    
    IF NEW.Dno IS NOT NULL THEN
    	UPDATE DEPT_STATS 
        SET Avg_salary = (SELECT AVG(Salary)
                             FROM EMPLOYEE
                             WHERE EMPLOYEE.Dno = NEW.Dno)
        WHERE DEPT_STATS.Dnumber = NEW.Dno;
   END IF;
END
-------------------------------------------------------------------
--
-- update a tuple
BEGIN
	IF (OLD.Dno <> NEW.Dno) THEN
    	UPDATE DEPT_STATS
        SET Emp_count = Emp_count + 1
        WHERE Dnumber = NEW.Dno;
        
        UPDATE DEPT_STATS 
        SET Avg_salary = (select AVG(Salary)
                          from EMPLOYEE
                          where EMPLOYEE.Dno = NEW.Dno)
        WHERE DEPT_STATS.Dnumber = NEW.Dno;
                         
    	UPDATE DEPT_STATS
        SET Emp_count = Emp_count - 1
        WHERE Dnumber = OLD.Dno;
        
    	UPDATE DEPT_STATS 
        SET Avg_salary = (select AVG(Salary)
                          from EMPLOYEE
                          where EMPLOYEE.Ssn != OLD.Ssn 
                          and EMPLOYEE.Dno = OLD.Dno)
        WHERE DEPT_STATS.Dnumber = OLD.Dno;
    END IF;
END
-------------------------------------------------------------------
--
-- trigger for employee exceeding 40 hrs on a new project entry
BEGIN
	DECLARE errMsg VARCHAR(100);
    DECLARE totalHours int(2);
    
    SELECT SUM(Hours) INTO totalHours
    	FROM WORKS_ON
    	WHERE WORKS_ON.Essn = NEW.Essn;
    
    IF NEW.Hours > 40 THEN
        SET errMsg = concat('You entered ', NEW.Hours, '. You currently work ', totalHours, '. You are over 40 hours.');
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = errMsg;
    END IF;
    
    IF totalHours + NEW.Hours > 40 THEN
        SET errMsg = concat('You entered ', NEW.Hours, '. You currently work ', totalHours, '. You are over 40 hours.');
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = errMsg;
    END IF;
END
-------------------------------------------------------------------
-- 
-- Function to calculate salary level
BEGIN
	DECLARE empSal double;
    DECLARE deptSal double;
    
    SELECT EMPLOYEE.Salary into empSal
    FROM EMPLOYEE
    WHERE EMPLOYEE.Ssn = ssn;
    
    SELECT DEPT_STATS.Avg_salary into deptSal
    FROM DEPT_STATS, EMPLOYEE
    WHERE DEPT_STATS.Dnumber = EMPLOYEE.Dno
    AND EMPLOYEE.Ssn = ssn;
    
    IF empSal > deptSal THEN
    	RETURN 'Above Average';
    END IF;

    IF empSal < deptSal THEN
    	RETURN 'Below Average';
	END IF;
    
    IF empSal = deptSal THEN
    	RETURN 'Average';
   	END IF;
END
-------------------------------------------------------------------