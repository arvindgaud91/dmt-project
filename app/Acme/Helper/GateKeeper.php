<?php
namespace Acme\Helper;
use Acme\Exceptions\GateKeeperException;
use Acme\Exceptions\TestException;
use \User;

/**
* GateKeeper will help identify roles and permissions
*/
class GateKeeper
{
	/**
	 * Will check is user has the permissions for the job.
	 *
	 */
	public static function check ($user, $permissions, $json = false)
	{
		$code = $json ? 99 : 100;

		if (! $user) throw new GateKeeperException("GateKeeper: user not found", $code);
		$requiredPermissions = (is_string($permissions)) ? [$permissions] : $permissions;

		if (count(array_diff($requiredPermissions, $user->permissions())) > 0 && ! Self::isSuperAdmin($user)) {
			throw new GateKeeperException("GateKeeper: user does not have permission", $code);
		}
		return true;
	}

	public static function isAllowed ($user, $permissions)
	{
		if (! $user) return false;
		$requiredPermissions = (is_string($permissions)) ? [$permissions] : $permissions;

		if (count(array_diff($requiredPermissions, $user->permissions())) > 0 && ! Self::isSuperAdmin($user)) {
			return false;
		}
		return true;
	}

	/**
	 * Will check is user has the right role for the job.
	 *
	 */
	public static function checkRoles ($user, $roles, $json = false)
	{
		$code = $json ? 99 : 100;

		if (! $user) throw new GateKeeperException("GateKeeper: user not found", $code);
		$roles = (is_array($roles)) ? $roles : [$roles];

		if (count(array_diff([$user->vendorDetails->type], $roles)) > 0 && ! Self::isSuperAdmin($user)) {
			throw new GateKeeperException("GateKeeper: user does not have permission", $code);
		}
		return true;
	}

	/**
	 * Will check is user has the right role for the job.
	 * Will also check if the user has been blacklisted from this job.
	 *
	 */
	public static function checkBlacklist ($user, $roles, $blacklist, $json = false)
	{
		$code = $json ? 99 : 100;

		if (! $user) throw new GateKeeperException("GateKeeper: user not found", $code);
		$roles = (is_array($roles)) ? $roles : [$roles];

		if (count(array_diff([$user->vendorDetails->type], $roles)) > 0 && ! Self::isSuperAdmin($user)) {
			throw new GateKeeperException("GateKeeper: user does not have permission", $code);
		}

		if (in_array($blacklist, $user->getBlacklist())) {
			throw new GateKeeperException("GateKeeper: user does not have permission", $code);
		}
		return true;
	}

	public static function isAllowedRoles ($user, $permissions)
	{
		if (! $user) return false;
		$requiredPermissions = (is_array($permissions)) ? $permissions : [$permissions];

		if (count(array_diff([$user->vendorDetails->type], $requiredPermissions)) > 0 && ! Self::isSuperAdmin($user)) {
			return false;
		}
		return true;
	}

	private static function isSuperAdmin ($user)
	{
		return $user->type == 9;
	}
}
