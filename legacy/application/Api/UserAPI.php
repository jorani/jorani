<?php
/**
 * This file is only used to generate the swagger documentation
 * @license https://opensource.org/licenses/MIT MIT
 */
namespace Jorani\Api;

use OpenApi\Attributes as OA;

class UserAPI
{
    #[OA\Get(
        path: "/api/users",
        summary: "List all users",
        description: "Get the list of all users stored in the database.",
        tags: ["Users"],
        security: [['jorani_auth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of users",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/User")
                )
            )
        ]
    )]
    #[OA\Get(
        path: "/api/users/{user_id}",
        summary: "Get a specific user",
        description: "Get the details of a specific user by their identifier.",
        tags: ["Users"],
        security: [['jorani_auth' => []]],
        parameters: [
            new OA\Parameter(
                name: "user_id",
                description: "Identifier of the user",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "User details",
                content: new OA\JsonContent(ref: "#/components/schemas/User")
            )
        ]
    )]
    #[OA\Response(response: 401, description: "Unauthorized")]
    #[OA\Response(response: 404, description: "User not found")]
    public function users(int $id = 0): void
    {
    }

    #[OA\Post(
        path: "/api/users/{send_email}",
        summary: "Create a new user",
        description: "Create a new user in the database. Returns the ID of the new user.",
        tags: ["Users"],
        security: [['jorani_auth' => []]],
        parameters: [
            new OA\Parameter(
                name: "send_email",
                description: "Send an email to the new user (TRUE/FALSE)",
                in: "path",
                required: false,
                schema: new OA\Schema(type: "boolean", default: false)
            )
        ],
        requestBody: new OA\RequestBody(
            description: "Properties of the new user",
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["firstname", "lastname", "login", "email"], // Hypothèse des champs obligatoires
                    properties: [
                        new OA\Property(property: "firstname", type: "string", description: "First name"),
                        new OA\Property(property: "lastname", type: "string", description: "Last name"),
                        new OA\Property(property: "login", type: "string", description: "Identifier used to login"),
                        new OA\Property(property: "email", type: "string", format: "email", description: "Email address"),
                        new OA\Property(property: "role", type: "integer", description: "Role binary mask"),
                        new OA\Property(property: "manager", type: "integer", description: "Validator manager ID"),
                        new OA\Property(property: "country", type: "integer"),
                        new OA\Property(property: "organization", type: "integer"),
                        new OA\Property(property: "contract", type: "integer"),
                        new OA\Property(property: "position", type: "integer"),
                        new OA\Property(property: "datehired", type: "string", format: "date"),
                        new OA\Property(property: "identifier", type: "string"),
                        new OA\Property(property: "language", type: "string", example: "en"),
                        new OA\Property(property: "ldap_path", type: "string"),
                        new OA\Property(property: "active", type: "boolean"),
                        new OA\Property(property: "timezone", type: "string"),
                        new OA\Property(property: "calendar", type: "string"),
                        new OA\Property(property: "user_properties", type: "string"),
                        new OA\Property(property: "picture", type: "string", description: "Base64 encoded picture")
                    ]
                )
            )
        )
    )]
    #[OA\Response(response: 200, description: "User created", content: new OA\JsonContent(properties: [new OA\Property(property: "id", type: "integer")]))]
    #[OA\Response(response: 401, description: "Unauthorized")]
    #[OA\Response(response: 422, description: "Missing mandatory fields or login not available")]
    public function createuser($sendEmail = FALSE): void
    {
    }

    #[OA\Patch(
        path: "/api/users/{user_id}",
        summary: "Update a user",
        description: "Update user properties. For PATCH method, ensure you use 'application/x-www-form-urlencoded'.",
        tags: ["Users"],
        security: [['jorani_auth' => []]],
        parameters: [
            new OA\Parameter(
                name: "user_id",
                description: "Identifier of the user",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            description: "Properties of user to update",
            content: new OA\MediaType(
                mediaType: "application/x-www-form-urlencoded",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "firstname", type: "string", description: "First name"),
                        new OA\Property(property: "lastname", type: "string", description: "Last name"),
                        new OA\Property(property: "login", type: "string", description: "Identifier used to login"),
                        new OA\Property(property: "email", type: "string", description: "Email address"),
                        new OA\Property(property: "role", type: "integer", description: "Role binary mask"),
                        new OA\Property(property: "manager", type: "integer", description: "Validator employee ID"),
                        new OA\Property(property: "country", type: "integer", description: "Country code"),
                        new OA\Property(property: "organization", type: "integer", description: "Entity/Organization ID"),
                        new OA\Property(property: "contract", type: "integer", description: "Contract ID"),
                        new OA\Property(property: "position", type: "integer", description: "Position ID"),
                        new OA\Property(property: "datehired", type: "string", format: "date", description: "Date hired"),
                        new OA\Property(property: "identifier", type: "string", description: "Internal company ID"),
                        new OA\Property(property: "language", type: "string", description: "Language ISO code"),
                        new OA\Property(property: "ldap_path", type: "string", description: "LDAP Path"),
                        new OA\Property(property: "active", type: "boolean", description: "Is user active"),
                        new OA\Property(property: "timezone", type: "string", description: "Timezone"),
                        new OA\Property(property: "calendar", type: "string", description: "External Calendar address"),
                        new OA\Property(property: "user_properties", type: "string", description: "Extra properties"),
                        new OA\Property(property: "picture", type: "string", description: "Profile picture (Base64)")
                    ]
                )
            )
        )
    )]
    #[OA\Response(response: 200, description: "User is updated")]
    #[OA\Response(response: 401, description: "Unauthorized")]
    #[OA\Response(response: 404, description: "User not found")]
    #[OA\Response(response: 422, description: "Nothing to update or wrong media type")]
    public function updateuser(int $userId): void
    {
    }

    #[OA\Delete(
        path: "/api/users/{user_id}",
        summary: "Delete a user",
        description: "Delete a user from the database. Note: This action is destructive and not recommended for active organizations.",
        tags: ["Users"],
        security: [['jorani_auth' => []]],
        parameters: [
            new OA\Parameter(
                name: "user_id",
                description: "Identifier of the user to delete",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ]
    )]
    #[OA\Response(response: 200, description: "User is deleted")]
    #[OA\Response(response: 401, description: "Unauthorized")]
    #[OA\Response(response: 404, description: "User not found")]
    public function deleteuser(int $userId): void
    {
    }

    #[OA\Get(
        path: "/api/users/{employee_id}/department",
        summary: "Get employee department",
        description: "Get the department details of a given employee",
        tags: ["Users", "Organization"],
        security: [['jorani_auth' => []]],
        parameters: [
            new OA\Parameter(
                name: "employee_id",
                description: "Identifier of the employee",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ]
    )]
    #[OA\Response(
        response: 200, description: "Department found",
        content: new OA\JsonContent(ref: "#/components/schemas/Department")
    )]
    #[OA\Response(response: 401, description: "Unauthorized")]
    #[OA\Response(response: 404, description: "Employee not found")]
    #[OA\Response(response: 422, description: "Invalid parameters")]
    public function userdepartment(int $employeeId): void
    {
    }

    #[OA\Get(
        path: "/api/users/{employee_id}/leaves",
        summary: "Get employee leave requests",
        description: "Get all the leave requests of a specific employee.",
        tags: ["Users", "Leaves"],
        security: [['jorani_auth' => []]],
        parameters: [
            new OA\Parameter(
                name: "employee_id",
                description: "Identifier of the employee",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ]
    )]
    #[OA\Response(
        response: 200,
        description: "List of leave requests",
        content: new OA\JsonContent(type: "array", items: new OA\Items(ref: "#/components/schemas/Leave"))
    )]
    #[OA\Response(response: 401, description: "Unauthorized")]
    #[OA\Response(response: 404, description: "Employee not found")]
    public function userleaves(int $employeeId): void
    {
    }

    #[OA\Get(
        path: "/api/users/{employee_id}/extras",
        summary: "Get employee overtime requests",
        description: "Get all the overtime requests of a specific employee.",
        tags: ["Users", "Overtime"],
        security: [['jorani_auth' => []]],
        parameters: [
            new OA\Parameter(
                name: "employee_id",
                description: "Identifier of the employee",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ]
    )]
    #[OA\Response(
        response: 200,
        description: "List of overtime requests",
        content: new OA\JsonContent(type: "array", items: new OA\Items(ref: "#/components/schemas/Overtime"))
    )]
    #[OA\Response(response: 401, description: "Unauthorized")]
    #[OA\Response(response: 404, description: "Employee not found")]
    public function userextras(int $employeeId): void
    {
    }

    #[OA\Get(
        path: "/api/users/{employee_id}/monthlypresence/{month}/{year}",
        summary: "Get monthly presence report",
        description: "Get the monthly presence report and statistics for a given employee.",
        tags: ["Users", "Leaves"],
        security: [['jorani_auth' => []]],
        parameters: [
            new OA\Parameter(
                name: "employee_id",
                description: "Identifier of the employee",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "month",
                description: "Month number (1-12)",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", minimum: 1, maximum: 12)
            ),
            new OA\Parameter(
                name: "year",
                description: "Full Year number (e.g. 2026)",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ]
    )]
    #[OA\Response(
        response: 200, description: "Monthly presence report",
        content: new OA\JsonContent(ref: "#/components/schemas/MonthlyPresence")
    )]
    #[OA\Response(response: 401, description: "Unauthorized")]
    #[OA\Response(response: 404, description: "Employee not found")]
    #[OA\Response(response: 422, description: "No Contract is attached to employee")]
    public function monthlypresence(int $employeeId, int $month, int $year): void
    {
    }

}
